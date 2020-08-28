# Assingments Manager
## Branches
master - this is on production server
staging - this code is ready for testing/should be stable
dev - this code is unstable and is under active development
## Setup
TODO: Make dbs if not found
edit creds in resources.
```bash
git update-index --skip-worktree resources/connectAdmin.php 
git update-index --skip-worktree resources/connectRaw.php 
```