# Sqlite-Wrapper
This is a Simple SQLite wrapper in php. Most of the SQL commands have been simplified as php functions. The Wrapper automatically creates an ID column for all the tables so u dont have to add it later. The Code is still a bit buggy. Feel free to edit.

Usage :
        $db = new DB("sqlite-database.db"); //initiaziles the database.
        
        To get any table from the DB use,
            $table = $db->getTable("table-name"); //return false if no table is found.
        
        To Create a new Table use,
            $db->createTable("new-table",array("column-name-1"=>DB::$TEXT,"column-name-2"=>DB::$INT)); //DB::$TEXT , DB::$INT are types, More types can be added and updated in the schema checking.
        
        To add a new Row to the Table use,
            $table->addRow(array("column-name-1"=>"value 1","column-name-2"=>2));
            
        To get a Row by ID use,
            $row = $table->getRowByID(1); // 1 is the id and row is a php array.
            
        To get a Row by Checking other fields,
            $rows = $table->getRowsByField(array("column-name-1"=>"value 1")); //array can have multiple fields, $rows is a php array.
            
        To update a Row with ID use,
            $table->updateRowByID(1,array("column-name-1"=>"new value 2")); //array can have multiple fields.
        
        To remove a Row with ID use,
            $table->deleteRowByID(1); // 1 is the id of the row.
        
        To remove a Table with name use ,
            $db->deleteTable("table-name"); // table-name is the table name;
        
