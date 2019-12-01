# theQuestion

Link - http://ec2-18-222-177-5.us-east-2.compute.amazonaws.com/~dillonrayseals/adaloSheets/sheet.php


TODO
 - fix cell event listeners
    - issue - cannot attach event listeners to dynamically created DOM elements
    - potential fix - event delegation
    - short-term solution - add "update sheet" button (not great UX, but functional)
 - add input types
    - db already structured to allow for this
    - backend already gets type from db
    - need to request type from user when creating rows/columns
    - need to set input type to number if appropriate
 - security
    - filter inputs (currently susceptible to sql injections)
    - escape outputs
 - styling
    - make everything more pretty (bootstrap)
    - move add row & add column buttons to the ends of the existing rows/columns
 - spreadsheet selection
    - currently access existing spreadsheets through text input
    - change this to GET?