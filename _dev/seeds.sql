INSERT INTO `entry_types` (`name`, `handle`, `field_config`) VALUES
('Page', 'page', '{
    "fields": [
        {
            "name": "title",
            "type": "text",
            "label": "Page Title",
            "required": true
        },
        {
            "name": "content",
            "type": "textarea",
            "label": "Page Content",
            "required": false
        }
    ]
}');