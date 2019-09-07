# Magento 2 Advertisement

This module facilitates an easy way to add advertises to your Magento store. Using this module admin can add and update advertise related content in between list of product's on product listing page. Admin can add/update banners, video's and content which is admin can added in block section. In block section admin can use wysiwyg editor.


## Installation Instruction

* Copy the content of the repo to the Magento 2 app/code/Magesanjay/Advertisement
* Run command:
<b>php bin/magento setup:upgrade</b>
* Run Command:
<b>php bin/magento setup:static-content:deploy</b>
* Now Flush Cache: <b>php bin/magento cache:flush</b>

## Configuration Instruction

* Go to **Admin** >> **Catalog** >> **Categories** >> Choose **Category** on which you want to display advertise blocks >> In **List Block Settings** Tab >> Click on **Add Section** button >> Choose type **Block**, select particular block from drop-down, Set position in text-box where you want to display Advertise block on Product listing page.
* You can delete any of block from **Delete** button from end of row.
