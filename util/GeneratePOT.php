<?php
/**
 * CLI Utility: Generates Cryptex specific POT file from ALL source files
 * @author Andi Dittrich
 * @version 0.1
 * @license MIT Style X11 License
 * @target CLI
 */
// use a3non pot-generator
require('A3nonPOTGenerator.php');
require('A3nonMetadataExtractor.php');
require('wptools/pot-ext-meta.php');

// switch working directory
chdir(dirname(dirname(__FILE__)));

// extract plugin metadata
$meta = A3nonMetadataExtractor::extract('Cryptex.php');

// ENLIGHTER SETUP - use metadata for author and version data
$generator = new A3nonPOTGenerator('lang', null, array(
			'package-name' => 'Cryptex',
			'package-version' => $meta['Version'],
			'translator-name' => $meta['Author']
));
$generator->generate();