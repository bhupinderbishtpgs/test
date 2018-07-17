<?php 

return [
    1 => [
        "id" => 1,
        "name" => "ProductWorkflow",
        "states" => [
            [
                "name" => "Open",
                "label" => "Open"
            ],
            [
                "name" => "In Progress",
                "label" => "In Progress"
            ],
            [
                "name" => "Sent to QA",
                "label" => "Sent to QA"
            ],
            [
                "name" => "QA Approved",
                "label" => "QA Approved"
            ],
            [
                "name" => "QA Disapproved",
                "label" => "QA Disapproved"
            ],
            [
                "name" => "QA Released",
                "label" => "QA Released"
            ],
            [
                "name" => "Published",
                "label" => "Published"
            ],
            [
                "name" => "Unpublished",
                "label" => "Unpublished"
            ],
            [
                "name" => "QA approved",
                "label" => "QA approved"
            ],
            [
                "name" => "QA disapproved",
                "label" => "QA disapproved"
            ],
            [
                "name" => "Product Disapproved",
                "label" => "Product Disapproved"
            ]
        ],
        "statuses" => [
            [
                "name" => "In Progress",
                "label" => "In Progress",
                "elementPublished" => FALSE
            ],
            [
                "name" => "QA Released",
                "label" => "QA Released",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Ready for QA approve",
                "label" => "Ready for QA approve",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Published",
                "label" => "Published",
                "elementPublished" => TRUE
            ],
            [
                "name" => "Unpublished",
                "label" => "Unpublished",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Open",
                "label" => "Open",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Approve Product",
                "label" => "Approve Product",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Sent to QA",
                "label" => "Sent to QA",
                "elementPublished" => FALSE
            ],
            [
                "name" => "QA Approved",
                "label" => "QA Approved",
                "elementPublished" => FALSE
            ],
            [
                "name" => "QA Disapproved",
                "label" => "QA Disapproved",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Web team QA disapproved",
                "label" => "Web team QA disapproved",
                "elementPublished" => FALSE
            ],
            [
                "name" => "QA approved",
                "label" => "QA approved",
                "elementPublished" => FALSE
            ],
            [
                "name" => "QA disapproved",
                "label" => "QA disapproved",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Product Disapproved",
                "label" => "Product Disapproved",
                "elementPublished" => FALSE
            ]
        ],
        "actions" => [
            [
                "name" => "Disapprove Product",
                "label" => "Disapprove Product",
                "transitionTo" => [
                    "QA Disapproved" => [
                        "QA Disapproved"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "Products have been QA disapproved. Please check once",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    13,
                    14,
                    15,
                    18
                ],
                "notificationUsers" => [
                    14
                ],
                "events" => [

                ]
            ],
            [
                "name" => "Publish Product",
                "label" => "Publish Product",
                "transitionTo" => [
                    "Published" => [
                        "Published"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    13,
                    18
                ],
                "notificationUsers" => [

                ],
                "events" => [
                    "before" => [
                        "WorkflowBundle\\EventListener\\WorkflowActionListener",
                        "publishData"
                    ]
                ]
            ],
            [
                "name" => "Unpublish Product",
                "label" => "Unpublish Product",
                "transitionTo" => [
                    "Unpublished" => [
                        "Unpublished"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    13,
                    18
                ],
                "notificationUsers" => [

                ],
                "events" => [
                    "before" => [
                        "WorkflowBundle\\EventListener\\WorkflowActionListener",
                        "unpublishData"
                    ]
                ]
            ],
            [
                "name" => "Send to QA",
                "label" => "Send to QA",
                "transitionTo" => [
                    "Sent to QA" => [
                        "Sent to QA"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "Please verify the products",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    12,
                    13
                ],
                "notificationUsers" => [
                    14,
                    15,
                    18
                ],
                "events" => [

                ]
            ],
            [
                "name" => "QA Release",
                "label" => "QA Release",
                "transitionTo" => [
                    "QA Released" => [
                        "QA Released"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "Products are QA released now.",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    13,
                    14,
                    18
                ],
                "notificationUsers" => [
                    18
                ],
                "events" => [

                ]
            ],
            [
                "name" => "Mark in progress",
                "label" => "Mark in progress",
                "transitionTo" => [
                    "In Progress" => [
                        "In Progress"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "Products are ready for sending to QA",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    13,
                    14,
                    18
                ],
                "notificationUsers" => [
                    12
                ],
                "events" => [

                ]
            ],
            [
                "name" => "Approve Product",
                "label" => "Approve Product",
                "transitionTo" => [
                    "QA Approved" => [
                        "QA Approved"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "Products are QA approved",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    13,
                    14,
                    15,
                    18
                ],
                "notificationUsers" => [
                    14,
                    18
                ],
                "events" => [

                ]
            ],
            [
                "name" => "QA Approve",
                "label" => "QA Approve",
                "transitionTo" => [
                    "QA Approved" => [
                        "QA approved"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    14,
                    13
                ],
                "notificationUsers" => [
                    14,
                    18
                ],
                "events" => [

                ]
            ],
            [
                "name" => "Disapprove QA product",
                "label" => "Disapprove QA product",
                "transitionTo" => [
                    "QA disapproved" => [
                        "QA disapproved"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    18,
                    13,
                    14
                ],
                "notificationUsers" => [
                    14
                ],
                "events" => [

                ]
            ],
            [
                "name" => "QA approved",
                "label" => "QA approved",
                "transitionTo" => [
                    "QA approved" => [
                        "QA approved"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    14,
                    13
                ],
                "notificationUsers" => [
                    14,
                    18
                ],
                "events" => [

                ]
            ],
            [
                "name" => "Disapprove product",
                "label" => "Disapprove product",
                "transitionTo" => [
                    "Product Disapproved" => [
                        "Product Disapproved"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    18,
                    13
                ],
                "notificationUsers" => [
                    14
                ],
                "events" => [

                ]
            ]
        ],
        "transitionDefinitions" => [
            "globalActions" => [

            ],
            "In Progress" => [
                "validActions" => [
                    "Send to QA" => NULL
                ]
            ],
            "Sent to QA" => [
                "validActions" => [
                    "Approve Product" => NULL,
                    "Disapprove Product" => NULL
                ]
            ],
            "QA Disapproved" => [
                "validActions" => [
                    "QA approved" => NULL
                ]
            ],
            "QA Approved" => [
                "validActions" => [
                    "QA Release" => NULL,
                    "Disapprove QA product" => NULL
                ]
            ],
            "QA approved" => [
                "validActions" => [
                    "QA Release" => NULL,
                    "Disapprove QA product" => NULL
                ]
            ],
            "QA Released" => [
                "validActions" => [
                    "Publish Product" => NULL,
                    "Disapprove product" => NULL
                ]
            ],
            "Product Disapproved" => [
                "validActions" => [
                    "QA approved" => NULL
                ]
            ],
            "Published" => [
                "validActions" => [
                    "Unpublish Product" => NULL,
                    "Mark in progress" => NULL
                ]
            ],
            "Unpublished" => [
                "validActions" => [
                    "Mark in progress" => NULL
                ]
            ],
            "Open" => [
                "validActions" => [
                    "Mark in progress" => NULL
                ]
            ],
            "QA disapproved" => [
                "validActions" => [
                    "QA approved" => NULL
                ]
            ]
        ],
        "defaultState" => "Open",
        "defaultStatus" => "Open",
        "allowUnpublished" => TRUE,
        "workflowSubject" => [
            "types" => [
                "object"
            ],
            "classes" => [
                24
            ],
            "assetTypes" => [

            ],
            "documentTypes" => [

            ]
        ],
        "enabled" => TRUE,
        "creationDate" => 1528960429,
        "modificationDate" => 1530288375
    ],
    2 => [
        "id" => 2,
        "name" => "BoxKitWorkflow",
        "states" => [
            [
                "name" => "Open",
                "label" => "Open"
            ],
            [
                "name" => "Saved",
                "label" => "Saved"
            ],
            [
                "name" => "Published",
                "label" => "Published"
            ],
            [
                "name" => "Unpublished",
                "label" => "Unpublished"
            ]
        ],
        "statuses" => [
            [
                "name" => "Saved",
                "label" => "Saved",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Open",
                "label" => "Open",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Unpublished",
                "label" => "Unpublished",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Published",
                "label" => "Published",
                "elementPublished" => TRUE
            ]
        ],
        "actions" => [
            [
                "name" => "Save Data",
                "label" => "Save Data",
                "transitionTo" => [
                    "Saved" => [
                        "Saved"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    16
                ],
                "notificationUsers" => [

                ],
                "events" => [

                ]
            ],
            [
                "name" => "Publish Data",
                "label" => "Publish Data",
                "transitionTo" => [
                    "Published" => [
                        "Published"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    16
                ],
                "notificationUsers" => [

                ],
                "events" => [
                    "before" => [
                        "WorkflowBundle\\EventListener\\WorkflowActionListener",
                        "publishData"
                    ]
                ]
            ],
            [
                "name" => "Unpublish Data",
                "label" => "Unpublish Data",
                "transitionTo" => [
                    "Unpublished" => [
                        "Unpublished"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    16
                ],
                "notificationUsers" => [

                ],
                "events" => [
                    "before" => [
                        "WorkflowBundle\\EventListener\\WorkflowActionListener",
                        "unpublishData"
                    ]
                ]
            ],
            [
                "name" => "Open Data",
                "label" => "Open Data",
                "transitionTo" => [
                    "Open" => [
                        "Open"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    16
                ],
                "notificationUsers" => [

                ],
                "events" => [

                ]
            ]
        ],
        "transitionDefinitions" => [
            "globalActions" => [

            ],
            "Saved" => [
                "validActions" => [
                    "Publish Data" => NULL,
                    "Save Data" => NULL
                ]
            ],
            "Open" => [
                "validActions" => [
                    "Save Data" => NULL,
                    "Publish Data" => NULL
                ]
            ],
            "Published" => [
                "validActions" => [
                    "Unpublish Data" => NULL,
                    "Open Data" => NULL
                ]
            ],
            "Unpublished" => [
                "validActions" => [
                    "Publish Data" => NULL,
                    "Save Data" => NULL
                ]
            ]
        ],
        "defaultState" => "Open",
        "defaultStatus" => "Open",
        "allowUnpublished" => TRUE,
        "workflowSubject" => [
            "types" => [
                "object"
            ],
            "classes" => [
                28
            ],
            "assetTypes" => [

            ],
            "documentTypes" => [

            ]
        ],
        "enabled" => TRUE,
        "creationDate" => 1529398560,
        "modificationDate" => 1530195979
    ],
    3 => [
        "id" => 3,
        "name" => "VendorsManufacturer",
        "states" => [
            [
                "name" => "Open",
                "label" => "Open"
            ],
            [
                "name" => "Saved",
                "label" => "Saved"
            ],
            [
                "name" => "Published",
                "label" => "Published"
            ],
            [
                "name" => "Unpublished",
                "label" => "Unpublished"
            ]
        ],
        "statuses" => [
            [
                "name" => "Saved",
                "label" => "Saved",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Open",
                "label" => "Open",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Unpublished",
                "label" => "Unpublished",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Published",
                "label" => "Published",
                "elementPublished" => TRUE
            ]
        ],
        "actions" => [
            [
                "name" => "Save Data",
                "label" => "Save Data",
                "transitionTo" => [
                    "Saved" => [
                        "Saved"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    11
                ],
                "notificationUsers" => [

                ],
                "events" => [

                ]
            ],
            [
                "name" => "Publish Data",
                "label" => "Publish Data",
                "transitionTo" => [
                    "Published" => [
                        "Published"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    11
                ],
                "notificationUsers" => [

                ],
                "events" => [
                    "before" => [
                        "WorkflowBundle\\EventListener\\WorkflowActionListener",
                        "publishData"
                    ]
                ]
            ],
            [
                "name" => "Unpublish Data",
                "label" => "Unpublish Data",
                "transitionTo" => [
                    "Unpublished" => [
                        "Unpublished"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    11
                ],
                "notificationUsers" => [

                ],
                "events" => [
                    "before" => [
                        "WorkflowBundle\\EventListener\\WorkflowActionListener",
                        "unpublishData"
                    ]
                ]
            ],
            [
                "name" => "Open Data",
                "label" => "Open Data",
                "transitionTo" => [
                    "Open" => [
                        "Open"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    11
                ],
                "notificationUsers" => [

                ],
                "events" => [

                ]
            ]
        ],
        "transitionDefinitions" => [
            "globalActions" => [

            ],
            "Saved" => [
                "validActions" => [
                    "Publish Data" => NULL,
                    "Save Data" => NULL
                ]
            ],
            "Open" => [
                "validActions" => [
                    "Save Data" => NULL,
                    "Publish Data" => NULL
                ]
            ],
            "Published" => [
                "validActions" => [
                    "Unpublish Data" => NULL,
                    "Open Data" => NULL
                ]
            ],
            "Unpublished" => [
                "validActions" => [
                    "Publish Data" => NULL,
                    "Save Data" => NULL
                ]
            ]
        ],
        "defaultState" => "Open",
        "defaultStatus" => "Open",
        "allowUnpublished" => TRUE,
        "workflowSubject" => [
            "types" => [
                "object"
            ],
            "classes" => [
                45,
                42
            ],
            "assetTypes" => [

            ],
            "documentTypes" => [

            ]
        ],
        "enabled" => TRUE,
        "creationDate" => 1529654085,
        "modificationDate" => 1530713626
    ],
    4 => [
        "id" => 4,
        "name" => "Component",
        "states" => [
            [
                "name" => "Open",
                "label" => "Open"
            ],
            [
                "name" => "Saved",
                "label" => "Saved"
            ],
            [
                "name" => "Published",
                "label" => "Published"
            ],
            [
                "name" => "Unpublished",
                "label" => "Unpublished"
            ]
        ],
        "statuses" => [
            [
                "name" => "Open",
                "label" => "Open",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Saved",
                "label" => "Saved",
                "elementPublished" => FALSE
            ],
            [
                "name" => "Published",
                "label" => "Published",
                "elementPublished" => TRUE
            ],
            [
                "name" => "Unpublished",
                "label" => "Unpublished",
                "elementPublished" => FALSE
            ]
        ],
        "actions" => [
            [
                "name" => "Save Data",
                "label" => "Save Data",
                "transitionTo" => [
                    "Saved" => [
                        "Saved"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    17
                ],
                "notificationUsers" => [

                ],
                "events" => [

                ]
            ],
            [
                "name" => "Publish Data",
                "label" => "Publish Data",
                "transitionTo" => [
                    "Published" => [
                        "Published"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    17
                ],
                "notificationUsers" => [

                ],
                "events" => [
                    "before" => [
                        "WorkflowBundle\\EventListener\\WorkflowActionListener",
                        "publishData"
                    ]
                ]
            ],
            [
                "name" => "Unpublish Data",
                "label" => "Unpublish Data",
                "transitionTo" => [
                    "Unpublished" => [
                        "Unpublished"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    17
                ],
                "notificationUsers" => [

                ],
                "events" => [
                    "before" => [
                        "WorkflowBundle\\EventListener\\WorkflowActionListener",
                        "unpublishData"
                    ]
                ]
            ],
            [
                "name" => "Open Data",
                "label" => "Open Data",
                "transitionTo" => [
                    "Open" => [
                        "Open"
                    ]
                ],
                "notes" => [
                    "required" => FALSE,
                    "title" => "",
                    "type" => ""
                ],
                "additionalFields" => [

                ],
                "users" => [
                    17
                ],
                "notificationUsers" => [

                ],
                "events" => [

                ]
            ]
        ],
        "transitionDefinitions" => [
            "globalActions" => [

            ],
            "Saved" => [
                "validActions" => [
                    "Publish Data" => NULL,
                    "Save Data" => NULL
                ]
            ],
            "Open" => [
                "validActions" => [
                    "Save Data" => NULL,
                    "Publish Data" => NULL
                ]
            ],
            "Published" => [
                "validActions" => [
                    "Unpublish Data" => NULL,
                    "Open Data" => NULL
                ]
            ],
            "Unpublished" => [
                "validActions" => [
                    "Publish Data" => NULL,
                    "Save Data" => NULL
                ]
            ]
        ],
        "defaultState" => "Open",
        "defaultStatus" => "Open",
        "allowUnpublished" => TRUE,
        "workflowSubject" => [
            "types" => [
                "object"
            ],
            "classes" => [
                29
            ],
            "assetTypes" => [

            ],
            "documentTypes" => [

            ]
        ],
        "enabled" => TRUE,
        "creationDate" => 1529654643,
        "modificationDate" => 1530195972
    ]
];
