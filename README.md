# wp-breezy-hr-openings

## Introduction

WordPress plugin intended to load the data from your jobs.breezy.hr/json web api endpoint and display it on your WordPress site wherever a shortcode is placed. 

## Shortcode attributes and default values
```
    'user' => 'jobs',
    'secondstocache' => '60',
    'format' => 'table',
    'linktarget' => '_blank',
```

*user*
> Breezy HR page account name. 
> Example: If your job posting collection is available at `initech.breezy.hr` then use `initech` here.

*secondstocache*
> Number of seconds Wordpress will cache the listings on the server side to improve performance and avoid HTTP 429 errors

*format*
> Embed openings in table rows or in divs and spans. `table` or `div`.

*linktarget*
> `<a>` tag target attribute

## Example
```
[brzyhropenings user=jobs format=div]
```
