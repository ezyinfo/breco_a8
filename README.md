# Presentation

This project is an example database interaction. For Icademie academic activity.

You will find Database structure in file db/Model.png


# Installation

You need to configure database access in inc/config.php, then execute inc/initdb.php to init the database tables.


# Usage

Just visit index.php and enter destination value

# Test

To test, you need phpunit.
On Linux, just install phpunit via sudo apt install phpunit
Then, execute test by calling :
$ phpunit tests/all.php
