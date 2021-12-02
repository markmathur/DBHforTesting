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
    - *NOW* We have a test `testSQL_avoidInjectionAttacks` that show `fail` until we have extinguished the risk if injection attacks.

## Later
- STR.php should not be in DBhandler-folder. It is environmental. 
  - Compare STR and ENV and see what to do. 

- We should not log onto database as `root`. Learn how to create a user. 
