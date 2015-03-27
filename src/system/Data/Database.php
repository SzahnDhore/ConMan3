<?php

namespace Szandor\ConMan\Data;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

use PDO;

/**
 * Tar hand om datahanteringen mellan systemet och databasen.
 */
class Database
{

    /**
     * Skapar en uppkoppling mot databasen.
     *
     * Systemet är byggt för att endast hantera en databas och ett schema och alla inställningar kan därför hämtas
     * centralt från x.settings. Eftersom PDO används kan funktionen lätt byggas om för andra typer av databaser än
     * MySQL. Funktionen är satt som private för att förhindra otillbörliga databasuppkopplingar.
     */
    private static function connect()
    {
        try {
            $pdo = new PDO('mysql:host=' . Data\Settings::db('host') . ';dbname=' . Data\Settings::db('dbname') . ';charset=utf8', Data\Settings::db('username'), Data\Settings::db('password'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("set names utf8");
            $out = $pdo;
        } catch(PDOException $error) {
            $out = $error->getMessage();
        }
        return $out;
    }

    /**
     * Skickar in data i databasen.
     *
     * Kopplingen sker via connect() och info om databas och tabell hämtas från dbinfo().
     * Använder sig av PDO och prepared statements för att förhindra SQL injection. Funktionen kan även skicka tillbaka
     * en normal SQL-query för att användas i debug-syfte.
     *
     * @param array $request En array som innehåller två nycklar: 'table' och 'data'.
     *   'table' är en sträng med namnet på tabellen vi vill skriva till (utan ev. prefix).
     *   'data' är en array som i sin tur innehåller en array per post som skall skrivas. Varje post-array innehåller en
     *     nyckel per kolumn. Nycklarna skall ha exakt samma namn som kolumnerna och det får inte finnas nycklar som inte
     *     har korresponderande kolumner. Tabellen förutsätts alltid ha kolumnerna 'datum_skapad' och 'datum_andrad' samt
     *     en ID-kolumn med tabellens namn samt suffixet '_id'.
     * @param bool $return_query Om denna sätts till true utför funktionen inga operationer utan returnerar bara en
     *   SQL-query.
     * @return string Returnerar ett felmeddelande om det inte gick att skriva till databasen, annars senaste ID:t.
     */
    public static function create($request, $return_query = false)
    {
        $table = Data\Settings::db('prefix') . $request['table'];
        $data = $request['data'];

        foreach ($data as $row_num => $row) {
            $row['date_created'] = (isset($row['date_created']) ? $row['date_created'] : date('Y-m-d H:i:s'));
            $row['date_updated'] = (isset($row['date_updated']) ? $row['date_updated'] : date('Y-m-d H:i:s'));
            $data[0]['date_created'] = (isset($data[0]['date_created']) ? $data[0]['date_created'] : date('Y-m-d H:i:s'));
            $data[0]['date_updated'] = (isset($data[0]['date_updated']) ? $data[0]['date_updated'] : date('Y-m-d H:i:s'));
            foreach ($row as $key => $value) {
                $new_key = $key . '_' . $row_num;
                $row[$new_key] = $value;
                $flat_data[$new_key] = $value;
                unset($row[$key]);
            }
            $rows[] = '(:' . implode(', :', array_keys($row)) . ')';
        }
        $rows = implode(',', $rows);

        if ($table == '') {
            $out = 'Error: No table selected.';
        } elseif ($data == '') {
            $out = 'Error: No data to insert.';
        } else {
            $out = '';
            $sql = 'INSERT INTO ' . $table . '(' . implode(', ', array_keys($data[0])) . ') VALUES ' . $rows . ';';

            $con = self::connect();
            $stmt = $con->prepare($sql);
            foreach ($flat_data as $field => $value) {
                $$field = $value;
                $stmt->bindValue(':' . $field, $$field);
                $out .= $field . ': ' . $$field . "\n";
            }
            if ($return_query) {
                $out = $sql . "\n\n" . $out;
            } else {
                try {
                    $stmt->execute();
                    $out = $con->lastInsertId();
                } catch(PDOException $error) {
                    $out = $error->getMessage();
                } catch(\Exception $error) {
                    $out = $error->errorInfo;
                }
            }

        }
        $con = null;
        return $out;
    }

    /**
     * Hämtar data från databasen.
     *
     * Kopplingen sker via connect() och info om databas och tabell hämtas från dbinfo().
     * Använder sig av PDO och prepared statements för att förhindra SQL injection. Funktionen kan även skicka tillbaka
     * en normal SQL-query för att användas i debug-syfte.
     *
     * @param array $query En array med info för att skapa en query.
     * @param bool $returnquery Om denna sätts till true utför funktionen inga operationer utan returnerar bara en
     *   SQL-query.
     */
    public static function read($request, $return_query = false)
    {
        $flat_data = array();

        if (is_array($request)) {
            $sql_select = (isset($request['select']) ? ($request['select'] === '*' ? '*' : '`' . $request['select']) . '`' : '*');
            $sql_table = (isset($request['table']) ? Data\Settings::db('prefix') . $request['table'] : false);
            $sql_orderby = (isset($request['orderby']) && preg_match('/^[a-z\\_]{1,}$/', $request['orderby']) === 1 ? ' ORDER BY ' . $request['orderby'] : '');
            $sql_order = (isset($request['desc']) && $request['desc'] === true ? ' DESC' : '');
            $sql_limit = (isset($request['limit']) && is_numeric($request['limit']) ? ' LIMIT ' . ($request['limit'] * 1) : ' LIMIT ' . 9999);
            $sql_offset = (isset($request['offset']) && is_numeric($request['offset']) ? ' OFFSET ' . ($request['offset'] * 1) : '');
            $sql_where = array();

            if (isset($request['where'])) {

                if (isset($request['where']['query'])) {
                    switch ($request['where']['query']) {
                        case 'and' :
                            $sql_query = 'AND';
                            break;

                        case 'or' :
                            $sql_query = 'OR';
                            break;

                        case 'between' :
                            $sql_query = 'BETWEEN';
                            break;

                        default :
                            $sql_query = 'AND';
                            break;
                    }
                } else {
                    $sql_query = 'AND';
                }

                if ($sql_query !== 'BETWEEN') {

                    if (is_array($request['where']['col']) && is_array($request['where']['values'])) {
                        foreach ($request['where']['col'] as $key => $column) {
                            $flat_data[$column . '_' . $key] = $request['where']['values'][$key];
                            $sql_values[$key] = $column . ' = :' . $column . '_' . $key;
                        }
                    } elseif (!is_array($request['where']['col']) && is_array($request['where']['values'])) {
                        foreach ($request['where']['values'] as $key => $column) {
                            $flat_data[$request['where']['col'] . '_' . $key] = $column;
                            $sql_values[$key] = $request['where']['col'] . ' = :' . $request['where']['col'] . '_' . $key;
                        }
                    } elseif (!is_array($request['where']['col']) && !is_array($request['where']['values'])) {
                        $flat_data[$request['where']['col'] . '_0'] = $request['where']['values'];
                        $sql_values[0] = $request['where']['col'] . ' = :' . $request['where']['col'] . '_0';
                    }

                    $sql_where = implode(' ' . $sql_query . ' ', $sql_values);

                } else {

                    if (!is_array($request['where']['col']) && is_array($request['where']['values'])) {
                        foreach ($request['where']['values'] as $key => $column) {
                            $flat_data[$request['where']['col'] . '_' . $key] = (string)$column;
                            $sql_values[$key] = ':' . $request['where']['col'] . '_' . $key . '';
                        }
                    }

                    $sql_where = '' . $request['where']['col'] . ' BETWEEN ' . implode(' AND ', $sql_values) . '';

                }

            } else {
                $sql_where = '1 = 1';
            }

            $sql_where = ' WHERE (' . $sql_where . ')';
            // $sql_where = ' WHERE ' . $sql_where;

        } else {
            $out = 'Error: Request badly formatted. Expected an array().';
        }

        $sql = 'SELECT ' . $sql_select . ' FROM ' . $sql_table . $sql_where . $sql_orderby . $sql_order . $sql_limit . $sql_offset . ';';

        $out = '';
        $con = self::connect();
        $stmt = $con->prepare($sql);
        foreach ($flat_data as $field => $value) {
            $stmt->bindValue(':' . $field, $value);
            $out .= ':' . $field . ' = ' . $value . "\n";
        }
        if ($return_query) {
            $out = $sql . "\n\n" . $out . "\n\n";
        } else {
            try {
                $stmt->execute();
                $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $error) {
                $out = $error->getMessage();
            }
        }

        $con = null;
        return $out;
    }

    /**
     * Uppdaterar data.
     */
    public static function update($request, $return_query = false)
    {
        $table = Settings::db('prefix') . $request['table'];
        $id_col = $request['table'] . '_id';
        $flat_data = array('id_val' => $request['id'], );
        $id_var = 'id_val';

        $flat_data['date_updated'] = date('Y-m-d H:i:s');
        $sql_set[] = 'date_updated' . ' = :' . 'date_updated';

        foreach ($request['values'] as $col => $value) {
            $flat_data[$col] = $value;
            $sql_set[] = $col . ' = :' . $col;
        }

        $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sql_set) . ' WHERE ' . $id_col . ' = :' . $id_var . ';';

        $out = '';
        $con = self::connect();
        $stmt = $con->prepare($sql);

        foreach ($flat_data as $field => $value) {
            $stmt->bindValue(':' . $field, $value);
            $out .= ':' . $field . ' = ' . $value . "\n";
        }

        if ($return_query) {
            $out = $sql . "\n\n" . $out . "\n\n";
        } else {
            try {
                $out = $stmt->execute();
            } catch(PDOException $error) {
                $out = $error->getMessage();
            }
        }

        return $out;
    }

    public static function delete($request, $return_query = false)
    {
        $table = Settings::db('prefix') . $request['table'];
        $id_col = $request['table'] . '_id';
        $flat_data = array('id_val' => $request['id'], );
        $id_var = 'id_val';

        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $id_col . ' = :' . $id_var . ';';

        $out = '';
        $con = self::connect();
        $stmt = $con->prepare($sql);

        foreach ($flat_data as $field => $value) {
            $stmt->bindValue(':' . $field, $value);
            $out .= ':' . $field . ' = ' . $value . "\n";
        }

        if ($return_query) {
            $out = $sql . "\n\n" . $out . "\n\n";
        } else {
            try {
                $out = $stmt->execute();
            } catch(PDOException $error) {
                $out = $error->getMessage();
            }
        }

        $con = null;
        return $out;
    }

}
