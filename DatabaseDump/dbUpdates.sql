LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (28,'BoxandKit'),(25,'Brand'),(40,'Color'),(29,'Component'),(41,'Ingredient'),(42,'Manufacturer'),(24,'Product'),(26,'ProductLine'),(44,'ProductTaxonomy'),(45,'Vendor');
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `objects` WRITE;
/*!40000 ALTER TABLE `objects` DISABLE KEYS */;
INSERT INTO `objects` VALUES (4,1,'folder','Master Data','/',0,1,1529399745,1530702194,2,2,NULL,NULL,NULL),(6,4,'folder','Colors','/Master Data/',NULL,1,1529399785,1529399785,2,2,NULL,NULL,NULL),(8,1,'folder','Vendors','/',NULL,1,1529399816,1529399816,2,2,NULL,NULL,NULL),(9,1,'folder','Manufacturers','/',NULL,1,1529399849,1529399849,2,2,NULL,NULL,NULL),(79,1,'folder','Brands','/',NULL,1,1530011124,1530011124,2,2,NULL,NULL,NULL),(80,1,'folder','Ingredients','/',NULL,1,1530011147,1530011147,2,2,NULL,NULL,NULL),(81,4,'folder','Product Taxonomy','/Master Data/',NULL,1,1530011195,1530011195,2,2,NULL,NULL,NULL),(84,1,'folder','Product Lines','/',NULL,1,1530011375,1530011375,2,2,NULL,NULL,NULL),(121,1,'folder','Birchbox Products','/',NULL,1,1530097741,1530097741,2,2,NULL,NULL,NULL),(122,121,'folder','Boxes & Kits','/Birchbox Products/',NULL,1,1530097770,1530097770,2,2,NULL,NULL,NULL),(123,121,'folder','Components','/Birchbox Products/',NULL,1,1530097784,1530097784,2,2,NULL,NULL,NULL),(124,121,'folder','Products','/Birchbox Products/',0,1,1530097800,1530686556,2,2,NULL,NULL,NULL);
/*!40000 ALTER TABLE `objects` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `search_backend_data` WRITE;
/*!40000 ALTER TABLE `search_backend_data` DISABLE KEYS */;
INSERT INTO `search_backend_data` VALUES (4,'/Master Data','object','folder','folder',1,1529399745,1530702194,2,2,'ID: 4  \nPath: /Master Data  \nMaster Data',''),
(6,'/Master Data/Colors','object','folder','folder',1,1529399785,1529399785,2,2,'ID: 6  \nPath: /Master Data/Colors  \nColors',''),
(81, '/Master Data/Product Taxonomy', 'object', 'folder', 'folder', 1, 1529399785, 1529399785, 2, 2, 'ID: 81  \nPath: /Master Data/Product Taxonomy  \nProduct Taxonomy', ''),
(8,'/Vendors','object','folder','folder',1,1529399816,1529399816,2,2,'ID: 8  \nPath: /Vendors  \nVendors',''),
(9,'/Manufacturers','object','folder','folder',1,1529399849,1529399849,2,2,'ID: 9  \nPath: /Manufacturers  \nManufacturers',''),
(79,'/Brands','object','folder','folder',1,1530011124,1531135350,2,2,'ID: 79  \nPath: /Brands  \nBrands',''),
(80,'/Ingredients','object','folder','folder',1,1530011147,1531135351,2,2,'ID: 80  \nPath: /Ingredients  \nIngredients',''),
(84,'/Product Lines','object','folder','folder',1,1530011375,1531135350,2,2,'ID: 84  \nPath: /Product Lines  \nProduct Lines',''),
(121,'/Birchbox Products','object','folder','folder',1,1530097741,1530097741,2,2,'ID: 121  \nPath: /Birchbox Products  \nBirchbox Products',''),
(122,'/Birchbox Products/Boxes & Kits','object','folder','folder',1,1530097770,1530097770,2,2,'ID: 122  \nPath: /Birchbox Products/Boxes & Kits  \nBoxes & Kits',''),
(123,'/Birchbox Products/Components','object','folder','folder',1,1530097784,1530097784,2,2,'ID: 123  \nPath: /Birchbox Products/Components  \nComponents',''),
(124,'/Birchbox Products/Products','object','folder','folder',1,1530097800,1530686556,2,2,'ID: 124  \nPath: /Birchbox Products/Products  \nProducts','');
/*!40000 ALTER TABLE `search_backend_data` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (11,0,'role','AccountOrFinance',NULL,NULL,NULL,NULL,NULL,NULL,0,1,'objects,tags_assignment,tags_configuration,tags_search',NULL,NULL,NULL,NULL,1,'','45,42',NULL,NULL,'default','','',NULL),(12,0,'role','Content',NULL,NULL,NULL,NULL,NULL,NULL,0,1,'objects,tags_assignment,tags_configuration,tags_search',NULL,NULL,NULL,NULL,1,'','',NULL,NULL,'default','','',NULL),(13,0,'role','Engineering',NULL,NULL,NULL,NULL,NULL,NULL,0,1,'objects,tags_assignment,tags_configuration,tags_search',NULL,NULL,NULL,NULL,1,'','24',NULL,NULL,'default','','',NULL),(14,0,'role','Merchandising',NULL,NULL,NULL,NULL,NULL,NULL,0,1,'objects,tags_assignment,tags_configuration,tags_search',NULL,NULL,NULL,NULL,1,'','24',NULL,NULL,'default','','',NULL),(15,0,'role','Planning',NULL,NULL,NULL,NULL,NULL,NULL,0,1,'objects,tags_assignment,tags_configuration,tags_search',NULL,NULL,NULL,NULL,1,'','',NULL,NULL,'default','','',NULL),(16,0,'role','Subscription',NULL,NULL,NULL,NULL,NULL,NULL,0,1,'objects,tags_assignment,tags_configuration,tags_search',NULL,NULL,NULL,NULL,1,'','28',NULL,NULL,'default','','',NULL),(17,0,'role','SupplyChain',NULL,NULL,NULL,NULL,NULL,NULL,0,1,'objects,tags_assignment,tags_configuration,tags_search',NULL,NULL,NULL,NULL,1,'','29',NULL,NULL,'default','','',NULL),(18,0,'role','WebProduction',NULL,NULL,NULL,NULL,NULL,NULL,0,1,'objects,tags_assignment,tags_configuration,tags_search',NULL,NULL,NULL,NULL,1,'','29,24',NULL,NULL,'default','','',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `users_workspaces_object` WRITE;
/*!40000 ALTER TABLE `users_workspaces_object` DISABLE KEYS */;
INSERT INTO `users_workspaces_object` VALUES (1,'/',11,1,1,0,0,0,0,0,0,1,0,1,'','',''),(8,'/Vendors',11,1,1,1,0,0,1,1,1,1,1,1,'','',''),(9,'/Manufacturers',11,1,1,1,0,0,1,1,1,1,1,1,'','',''),(1,'/',12,1,1,0,0,0,0,0,0,1,1,1,'','',''),(124,'/Birchbox Products/Products',12,1,1,1,0,0,0,0,0,1,1,1,'','',''),(1,'/',13,1,1,0,0,0,0,0,0,1,1,1,'','',''),(124,'/Birchbox Products/Products',13,1,1,1,0,0,1,1,1,1,1,1,'','',''),(1,'/',14,1,1,0,0,0,0,0,0,1,1,1,'','',''),(124,'/Birchbox Products/Products',14,1,1,1,0,0,0,0,1,1,1,1,'','',''),(1,'/',15,1,1,0,0,0,0,0,0,1,1,1,'','',''),(124,'/Birchbox Products/Products',15,1,1,1,0,0,0,0,0,1,1,1,'','',''),(1,'/',16,1,1,0,0,0,0,0,0,1,1,1,'','',''),(122,'/Birchbox Products/Boxes & Kits',16,1,1,1,0,0,1,1,1,1,1,1,'','',''),(1,'/',17,1,1,0,0,0,0,0,0,1,1,1,'','',''),(123,'/Birchbox Products/Components',17,1,1,1,0,0,1,1,1,1,1,1,'','',''),(1,'/',18,1,1,0,0,0,0,0,0,1,1,1,'','',''),(124,'/Birchbox Products/Products',18,1,1,1,0,0,0,0,0,1,1,1,'','','');
/*!40000 ALTER TABLE `users_workspaces_object` ENABLE KEYS */;
UNLOCK TABLES;
