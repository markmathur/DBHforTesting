# TODO

## Imminent
- **DONE** Create a visual over the process that happens in the program. 
- Start outsourcing functionality into new files, in `/lib`. 
  - Write end-to-end-tests.
    - **DONE**This will make sure the Unpacker-extraction did't fail. 
    - **DONE** Send in a mocked DBhandler-object (instead of the `$this`).
  - Then
    - **DONE** Break out stuff from Unpacker, and write UNIT-tests for that.  
    - To test that storing stuff would work, we can first break out an SQL-generator, and then send stuff into that and see that the right SQL is written. 
    - **DONE** ** Read what vulnerabilities exist. SQL injection doesn't seem to be a risk. 
- Implement prepared SQL statements
  - **DONE** Start off with `GetPostWithId()` because it is does not effect the database with testing.
  - **DONE** `Do StorePost()` - this is harder, because `Extractor` returns a string for the SQL-statement, and we now want separate column names and values. 
  - *NOW* What should DBhandler return if SELECT returns no hits? An empty array or `false`? An empty array. *False should only be returned when DB disfunctions*. 
  - The SRBtest should react with red if something goes wrong. 
  - Add test on StmtHandler/getPostsByCriteria in `SetupRunBreakdownTest.php`. It should return an empty array if no hits.
  

## Later
- STR.php should not be in DBhandler-folder. It is environmental. 
  - Compare STR and ENV and see what to do. 

- We should not log onto database as `root`. Learn how to create a user. 
