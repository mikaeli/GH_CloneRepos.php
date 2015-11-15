# GH_CloneRepos.php

GH_CloneRepos.php is minimal tool to clone all users public GitHub repositories.
It is written in php and it uses git for cloning.

GH_CloneRepos.php creates directory with user/organization name,
fetches list of repositories page by page using GitHub API
and clones every repository with git.



## Usage

php GH_CloneRepos.php [username]

Where username is either usename or oraganization name.

## Requirements

GH_CloneRepos.php uses **curl** and **json** php extensions to
fetch data from GitHub API and parse the response.

**Git** is used to clone repositories.


## Bugs

GH_CloneRepos.php can only clone to fresh directory.
It cannot update previous repositories.
For updating you need to manually pull changes to your local repositories.

