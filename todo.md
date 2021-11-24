# TODO

## Imminent
- Create a visual over the process that happens in the program. 
- Start outsourcing functionality into new files, in `/lib`. 
  - Write end-to-end-tests.
    - This will make sure the Unpacker-extraction did't fail. 
    - Send in a mocked DBhandler-object (instead of the `$this`).
  - Then
    - Break out stuff from Unpacker, and write UNIT-tests for that.  

## Later
- STR.php should not be in DBhandler-folder. It is environmental. 
  - Compare STR and ENV and see what to do. 

- We should not log onto database as `root`. Learn how to create a user. 
