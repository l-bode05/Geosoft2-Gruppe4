-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 05. Feb 2015 um 19:39
-- Server Version: 5.5.41
-- PHP-Version: 5.3.10-1ubuntu3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `test`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `answer_to` int(11) NOT NULL,
  `content` varchar(2555) NOT NULL,
  `title` varchar(60) NOT NULL,
  `timecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timeedit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `resources` varchar(255) NOT NULL,
  `geodata_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tags_ids` varchar(255) NOT NULL,
  `permalink` varchar(255) NOT NULL,
  `av_rating` float NOT NULL,
  `ratings_num` int(11) NOT NULL,
  `position` point NOT NULL,
  `positionx` double NOT NULL,
  `positiony` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=113 ;

--
-- Daten für Tabelle `comments`
--

INSERT INTO `comments` (`id`, `answer_to`, `content`, `title`, `timecreated`, `timeedit`, `resources`, `geodata_id`, `user_id`, `tags_ids`, `permalink`, `av_rating`, `ratings_num`, `position`, `positionx`, `positiony`) VALUES
(98, 0, 'Temperature in USA', 'Temp', '2015-02-05 12:58:43', '0000-00-00 00:00:00', '', 59, 42, ',31,29,50,51,', 'temp', 0, 0, '', 42.81152174509788, -105.46875),
(99, 0, 'Paris traffic map', 'Paris cen.', '2015-02-05 13:11:18', '0000-00-00 00:00:00', '', 60, 407840, ',52,', 'paris cen', 0, 0, '', 48.76705193388751, 2.29888916015625),
(102, 0, 'The precipitation is very high at the moment. Danger of flooding.', 'Danger of flooding West-Coast', '2015-02-05 13:12:33', '0000-00-00 00:00:00', '', 62, 302278, ',54,29,55,56,57,', 'danger of flooding westcoast', 0, 0, '', 42.94033923363183, -122.34374999999999),
(103, 0, 'Westminister area', 'Westminster', '2015-02-05 13:20:28', '0000-00-00 00:00:00', '', 63, 339444, ',23,', 'westminster', 0, 0, '', 51.502652102264484, -0.12578487396240232),
(105, 0, 'You will love it. Best regards, Ruediger.', 'Ruediger''s best cycleway', '2015-02-05 13:22:34', '0000-00-00 00:00:00', '', 65, 48, ',59,60,61,', 'ruedigers best cycleway', 0, 0, '', 49.83975383204511, 8.925018310546875),
(107, 0, 'Maps of zambia', 'Zambia', '2015-02-05 13:22:38', '0000-00-00 00:00:00', '', 66, 339444, ',4,', 'zambia', 0, 0, '', -7.449624260197816, -17.402343749999996),
(109, 0, 'DTK25 for anything', 'DTK25', '2015-02-05 16:06:29', '0000-00-00 00:00:00', '', 67, 355035, ',60,62,63,', 'dtk25', 0, 0, '', 51.892596535517995, 7.664337158203126),
(111, 0, '123', 'WMS NRW', '2015-02-05 16:08:37', '0000-00-00 00:00:00', '', 68, 701118, ',4,', 'wms nrw', 0, 0, '', 51.96193280927054, 7.62897491455078);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `geodata`
--

CREATE TABLE IF NOT EXISTS `geodata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL,
  `name` varchar(60) NOT NULL,
  `boundingbox` multipoint NOT NULL,
  `entrycreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `datestamp` date NOT NULL DEFAULT '0000-00-00',
  `ratings_sum` float NOT NULL,
  `ratings_count` smallint(6) NOT NULL,
  `spatialexp` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=70 ;

--
-- Daten für Tabelle `geodata`
--

INSERT INTO `geodata` (`id`, `url`, `name`, `boundingbox`, `entrycreated`, `datestamp`, `ratings_sum`, `ratings_count`, `spatialexp`) VALUES
(59, 'http://gis.srh.noaa.gov/arcgis/services/NDFDTemps/MapServer/WMSServer', '', '', '2015-02-05 13:37:01', '2004-02-20', 4, 2, ''),
(60, 'http://www.arcgis.com/home/webmap/viewer.html?webmap=c3bae306b5ab44ab9d9559e37085dcd7&extent=2.3158,', '', '', '2015-02-05 13:12:52', '0000-00-00', 4, 2, ''),
(61, 'http://www.arcgis.com/home/webmap/viewer.html?webmap=c3bae306b5ab44ab9d9559e37085dcd7&extent=2.3158,', '', '', '2015-02-05 13:12:23', '0000-00-00', 3, 1, ''),
(62, 'http://nowcoast.noaa.gov/wms/com.esri.wms.Esrimap/obs', '', '', '2015-02-05 13:13:24', '2005-02-20', 8, 2, '{"type":"Feature","properties":{},"geometry":{"type":"Polygon","coordinates":[[[-124.62890625,35.02999636902566],[-124.62890625,48.574789910928864],[-115.13671875,48.574789910928864],[-115.13671875,35.02999636902566],[-124.62890625,35.02999636902566]]]}}'),
(63, 'http://harrywood.co.uk/maps/examples/leaflet/mapperz-kml-example.kml', '', '', '2015-02-05 16:07:45', '2004-02-20', 0.5, 1, '{\r\n    "type": "FeatureCollection",\r\n    "features": [\r\n        {\r\n            "type": "Feature",\r\n            "geometry": {\r\n                "type": "Point",\r\n                "coordinates": [\r\n                    -0.127458,\r\n                    51.503281,\r\n                    0\r\n                ]\r\n            },\r\n            "properties": {\r\n                "name": "10 Downing Street",\r\n                "styleUrl": "#style1",\r\n                "styleHash": "3685aba4",\r\n                "description": "Mapperz invited to Tea with the PM<br><br><img src=\\"http://tbn0.google.com/images?q=tbn:arc9bfQDjkwulM:http://europeforvisitors.com/europe/galleries/uk/uk_photos/london_10_downing_street_625056.jpg\\"><br>"\r\n            }\r\n        },\r\n        {\r\n            "type": "Feature",\r\n            "geometry": {\r\n                "type": "Polygon",\r\n                "coordinates": [\r\n                    [\r\n                        [\r\n                            -0.13064,\r\n                            51.505829,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12814,\r\n                            51.506882,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12762,\r\n                            51.50703,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.126922,\r\n                            51.50555,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12654,\r\n                            51.504169,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12634,\r\n                            51.50404,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12629,\r\n                            51.503181,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12636,\r\n                            51.501099,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12965,\r\n                            51.501369,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12948,\r\n                            51.502579,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12912,\r\n                            51.50325,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12889,\r\n                            51.503811,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12936,\r\n                            51.50518,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.13064,\r\n                            51.505829,\r\n                            0\r\n                        ]\r\n                    ]\r\n                ]\r\n            },\r\n            "properties": {\r\n                "name": "Mapperz Security Zone",\r\n                "styleUrl": "#style3",\r\n                "styleHash": "-76bfc766",\r\n                "description": "Armed Guard Patrol Zone"\r\n            }\r\n        },\r\n        {\r\n            "type": "Feature",\r\n            "geometry": {\r\n                "type": "LineString",\r\n                "coordinates": [\r\n                    [\r\n                        -0.12748,\r\n                        51.503254,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.12615,\r\n                        51.503159,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.124883,\r\n                        51.503094,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.123981,\r\n                        51.503132,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.123402,\r\n                        51.503105,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.121815,\r\n                        51.503063,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.121257,\r\n                        51.504215,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.120613,\r\n                        51.505497,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.119583,\r\n                        51.506886,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.118102,\r\n                        51.507954,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.117265,\r\n                        51.508289,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.116214,\r\n                        51.508621,\r\n                        0\r\n                    ]\r\n                ]\r\n            },\r\n            "properties": {\r\n                "name": "Escape Route 1",\r\n                "styleUrl": "#style4",\r\n                "styleHash": "482694ce",\r\n                "description": "Security Escape Route (under 2 minutes)"\r\n            }\r\n        },\r\n        {\r\n            "type": "Feature",\r\n            "geometry": {\r\n                "type": "Polygon",\r\n                "coordinates": [\r\n                    [\r\n                        [\r\n                            -0.12188,\r\n                            51.506714,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.118339,\r\n                            51.505577,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.117996,\r\n                            51.504829,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.11703,\r\n                            51.504295,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.118489,\r\n                            51.500851,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12424,\r\n                            51.500835,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.123746,\r\n                            51.503265,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.122566,\r\n                            51.506405,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12188,\r\n                            51.506714,\r\n                            0\r\n                        ]\r\n                    ]\r\n                ]\r\n            },\r\n            "properties": {\r\n                "name": "Shape 2",\r\n                "styleUrl": "#style5",\r\n                "styleHash": "-3f3db238"\r\n            }\r\n        },\r\n        {\r\n            "type": "Feature",\r\n            "geometry": {\r\n                "type": "LineString",\r\n                "coordinates": [\r\n                    [\r\n                        -0.125484,\r\n                        51.495277,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.115442,\r\n                        51.497044,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.118275,\r\n                        51.510239,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.141106,\r\n                        51.500408,\r\n                        0\r\n                    ]\r\n                ]\r\n            },\r\n            "properties": {\r\n                "name": "Route & Directions",\r\n                "styleUrl": "#style2",\r\n                "styleHash": "27b60fa",\r\n                "description": "<div dir=\\"ltr\\">Edit your My Maps directions here.</div>",\r\n                "_SnapToRoads": "true"\r\n            }\r\n        }\r\n    ]\r\n}'),
(64, 'http://harrywood.co.uk/maps/examples/leaflet/mapperz-kml-example.kml', '', '', '2015-02-05 13:20:28', '2004-02-20', 0, 0, '{\r\n    "type": "FeatureCollection",\r\n    "features": [\r\n        {\r\n            "type": "Feature",\r\n            "geometry": {\r\n                "type": "Point",\r\n                "coordinates": [\r\n                    -0.127458,\r\n                    51.503281,\r\n                    0\r\n                ]\r\n            },\r\n            "properties": {\r\n                "name": "10 Downing Street",\r\n                "styleUrl": "#style1",\r\n                "styleHash": "3685aba4",\r\n                "description": "Mapperz invited to Tea with the PM<br><br><img src=\\"http://tbn0.google.com/images?q=tbn:arc9bfQDjkwulM:http://europeforvisitors.com/europe/galleries/uk/uk_photos/london_10_downing_street_625056.jpg\\"><br>"\r\n            }\r\n        },\r\n        {\r\n            "type": "Feature",\r\n            "geometry": {\r\n                "type": "Polygon",\r\n                "coordinates": [\r\n                    [\r\n                        [\r\n                            -0.13064,\r\n                            51.505829,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12814,\r\n                            51.506882,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12762,\r\n                            51.50703,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.126922,\r\n                            51.50555,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12654,\r\n                            51.504169,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12634,\r\n                            51.50404,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12629,\r\n                            51.503181,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12636,\r\n                            51.501099,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12965,\r\n                            51.501369,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12948,\r\n                            51.502579,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12912,\r\n                            51.50325,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12889,\r\n                            51.503811,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12936,\r\n                            51.50518,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.13064,\r\n                            51.505829,\r\n                            0\r\n                        ]\r\n                    ]\r\n                ]\r\n            },\r\n            "properties": {\r\n                "name": "Mapperz Security Zone",\r\n                "styleUrl": "#style3",\r\n                "styleHash": "-76bfc766",\r\n                "description": "Armed Guard Patrol Zone"\r\n            }\r\n        },\r\n        {\r\n            "type": "Feature",\r\n            "geometry": {\r\n                "type": "LineString",\r\n                "coordinates": [\r\n                    [\r\n                        -0.12748,\r\n                        51.503254,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.12615,\r\n                        51.503159,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.124883,\r\n                        51.503094,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.123981,\r\n                        51.503132,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.123402,\r\n                        51.503105,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.121815,\r\n                        51.503063,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.121257,\r\n                        51.504215,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.120613,\r\n                        51.505497,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.119583,\r\n                        51.506886,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.118102,\r\n                        51.507954,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.117265,\r\n                        51.508289,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.116214,\r\n                        51.508621,\r\n                        0\r\n                    ]\r\n                ]\r\n            },\r\n            "properties": {\r\n                "name": "Escape Route 1",\r\n                "styleUrl": "#style4",\r\n                "styleHash": "482694ce",\r\n                "description": "Security Escape Route (under 2 minutes)"\r\n            }\r\n        },\r\n        {\r\n            "type": "Feature",\r\n            "geometry": {\r\n                "type": "Polygon",\r\n                "coordinates": [\r\n                    [\r\n                        [\r\n                            -0.12188,\r\n                            51.506714,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.118339,\r\n                            51.505577,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.117996,\r\n                            51.504829,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.11703,\r\n                            51.504295,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.118489,\r\n                            51.500851,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12424,\r\n                            51.500835,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.123746,\r\n                            51.503265,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.122566,\r\n                            51.506405,\r\n                            0\r\n                        ],\r\n                        [\r\n                            -0.12188,\r\n                            51.506714,\r\n                            0\r\n                        ]\r\n                    ]\r\n                ]\r\n            },\r\n            "properties": {\r\n                "name": "Shape 2",\r\n                "styleUrl": "#style5",\r\n                "styleHash": "-3f3db238"\r\n            }\r\n        },\r\n        {\r\n            "type": "Feature",\r\n            "geometry": {\r\n                "type": "LineString",\r\n                "coordinates": [\r\n                    [\r\n                        -0.125484,\r\n                        51.495277,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.115442,\r\n                        51.497044,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.118275,\r\n                        51.510239,\r\n                        0\r\n                    ],\r\n                    [\r\n                        -0.141106,\r\n                        51.500408,\r\n                        0\r\n                    ]\r\n                ]\r\n            },\r\n            "properties": {\r\n                "name": "Route & Directions",\r\n                "styleUrl": "#style2",\r\n                "styleHash": "27b60fa",\r\n                "description": "<div dir=\\"ltr\\">Edit your My Maps directions here.</div>",\r\n                "_SnapToRoads": "true"\r\n            }\r\n        }\r\n    ]\r\n}'),
(65, 'http://www.bernhard-gaul.de/beispiel/Jostweg.gpx', '', '', '2015-02-05 13:23:56', '2016-07-20', 1, 1, '{\r\n    "type": "FeatureCollection",\r\n    "features": []\r\n}'),
(66, 'http://zambia.com/webmaps/', '', '', '2015-02-05 16:05:57', '0000-00-00', 4, 1, '{"type":"Feature","properties":{},"geometry":{"type":"Polygon","coordinates":[[[19.072265625,-18.062312304546726],[19.072265625,-10.919617760254685],[30.41015625,-10.919617760254685],[30.41015625,-18.062312304546726],[19.072265625,-18.062312304546726]]]}}'),
(67, 'http://www.wms.nrw.de/geobasis/wms_nw_dtk25', '', '', '2015-02-05 16:06:29', '2005-02-20', 0, 0, '\r\n	{\r\n	  "type": "FeatureCollection",\r\n	  "features": [\r\n		{\r\n		  "type": "Feature",\r\n		  "properties": {},\r\n		  "geometry": {\r\n			"type": "Polygon",\r\n			"coordinates": [\r\n			  [\r\n				[\r\n				  5.72499,\r\n				  50.1506\r\n				],\r\n				[\r\n				  5.72499,\r\n				  52.602\r\n				],\r\n				[\r\n				  9.53154,\r\n				  52.602\r\n				],\r\n				[\r\n				  9.53154,\r\n				  50.1506\r\n				],\r\n				[\r\n				  5.72499,\r\n				  50.1506\r\n				]\r\n			  ]\r\n			]\r\n		  }\r\n		}\r\n	  ]\r\n	}\r\n\r\n'),
(68, 'http://www.wms.nrw.de/geobasis/wms_nw_dgk5', '', '', '2015-02-05 16:08:37', '0000-00-00', 0, 0, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `staticpages`
--

CREATE TABLE IF NOT EXISTS `staticpages` (
  `id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `content` varchar(2555) NOT NULL,
  `permalink` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `permalink` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

--
-- Daten für Tabelle `tags`
--

INSERT INTO `tags` (`id`, `name`, `permalink`) VALUES
(1, 'name', 'name'),
(2, 'uber', 'uber'),
(3, 'kunk', 'kunk'),
(4, '', ''),
(5, 'rrrrrr', 'rrrrrr'),
(6, 'ff', 'ff'),
(7, 'see', 'see'),
(8, ' teich', ' teich'),
(9, ' poi', ' poi'),
(10, 'tag1', 'tag1'),
(11, ' tag2', ' tag2'),
(12, ' tag3', ' tag3'),
(13, 'test', '-1'),
(14, 'undefined', '-2'),
(15, 'Apple', '-3'),
(17, 'Orange', '-5'),
(23, 'poi', 'poi'),
(24, 'p22oi', 'p22oi'),
(25, 'kanal', 'kanal'),
(26, 'kanal', 'kanal'),
(27, 'teichpoi', 'teichpoi'),
(28, 'teich', 'teich'),
(29, 'usa', 'usa'),
(30, 'usa', 'usa'),
(31, 'temperature', 'temperature'),
(32, 'nrw', 'nrw'),
(33, 'nrw', 'nrw'),
(34, 'Westminster', 'westminster'),
(35, 'Westminster', 'westminster'),
(36, 'kml', 'kml'),
(37, 'sss', 'sss'),
(38, 'sss', 'sss'),
(39, 'sdds', 'sdds'),
(40, 'sdds', 'sdds'),
(41, 'London', 'london'),
(42, 'Themse', 'themse'),
(43, 'a', 'a'),
(44, 'a', 'a'),
(45, 'Saint', 'saint'),
(46, 'James''s', 'jamess'),
(47, 'Park', 'park'),
(48, 'atomkrieg', 'atomkrieg'),
(49, 'atomkrieg', 'atomkrieg'),
(50, 'united', 'united'),
(51, 'states', 'states'),
(52, 'Traffic', 'traffic'),
(53, 'Flood', 'flood'),
(54, 'Flood', 'flood'),
(55, 'West', 'west'),
(56, 'Coast', 'coast'),
(57, 'Precipitation', 'precipitation'),
(58, 'Cycleway', 'cycleway'),
(59, 'Cycleway', 'cycleway'),
(60, 'Germany', 'germany'),
(61, 'Beautiful', 'beautiful'),
(62, 'Bl', 'bl'),
(63, 'ahsashsd', 'ahsashsd');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isAnonym` tinyint(1) NOT NULL,
  `name` varchar(60) NOT NULL,
  `cookie` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `registertime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `isAnonym`, `name`, `cookie`, `mail`, `password`, `registertime`) VALUES
(41, 0, 'TT69', '', 'tobias.tasse@googlemail.com', 'cfcd208495d565ef66e7dff9f98764da', '2015-02-04 14:34:06'),
(42, 0, 'Marike', '', 'marikemeijer@googlemail.com', 'cfcd208495d565ef66e7dff9f98764da', '2015-02-04 17:57:13'),
(43, 0, 'Test', '', 'ma@goo.de', '6fd31888308363d948ea4904c5e9bc5b', '2015-02-05 09:43:35'),
(44, 0, 'Test', '', 'ma@goo.de', '6fd31888308363d948ea4904c5e9bc5b', '2015-02-05 09:43:35'),
(45, 0, 'RÃ¼diger73', '', 'RÃ¼diger73@hotmail.com', 'cfcd208495d565ef66e7dff9f98764da', '2015-02-05 11:52:59'),
(46, 0, 'RÃ¼diger73', '', 'RÃ¼diger73@hotmail.com', 'cfcd208495d565ef66e7dff9f98764da', '2015-02-05 11:52:59'),
(47, 0, 'Luisa', '', 'lulu_isa@web.de', 'cfcd208495d565ef66e7dff9f98764da', '2015-02-05 12:54:12'),
(48, 0, 'RÃ¼diger45', '', 'ruediger45@hotmail.com', 'fcea920f7412b5da7be0cf42b8c93759', '2015-02-05 13:17:05'),
(49, 0, 'nuest', '', 'daniel.nuest@uni-muenster.de', 'cfcd208495d565ef66e7dff9f98764da', '2015-02-05 16:04:59'),
(50, 0, 'test5', '', 'test@weeb.de', 'b0baee9d279d34fa1dfd71aadb908c3f', '2015-02-05 16:09:41');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
