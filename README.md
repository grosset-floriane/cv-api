# CV Api
Api for cv-in-react project
You can try it here:
https://florianegrosset.com/api/cv/read.php

## Intro

CV Api is an API made with PHP and MySQL to be used originally in the CV in React project.
For now, this Api is only meant to retrieve the data of my CV that are stored on my website: florianegrosset.com
and convert it into JSON, so it can be fetch by a React script.

## Inspiration

The base concept of an Api in PHP and MySQL to effectuate all types of actions (read, create, delete, update)
was thouroughly explained in this tutorial from which I got the key elements from:
https://webdamn.com/create-simple-rest-api-with-php-mysql/

## Table structure

### cv (general)
| cv_id | cv_name | cv_categories      |

| int   | varchar | text (, separated) |

### cv_categories
| category_id | category_name | categories_slug |

| int         | varchar       | varchar         |

### cv_personalinfo
| my_name | tagline | address | phone_number | email   | website | cv_id |

| varcher | text    | text    | varchar      | varchar | varchar | int   |

### cv_content
| content_id | cv_id | title   | subtitle | description | text       | start_date | end_date | category | link_to_post | image |

| int        | int   | varchar | varchar  | text        | mediumtext | date       | date     | int      | varchar      | text  |

## Next possible developments

As it is only a Read Api for now, I will add up other functionalities in order to add and remove content from the 
current cv. 
I already planned the possibility to create multiple CVs with different users, but let's see where it goes.
