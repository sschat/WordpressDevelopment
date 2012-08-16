<?php
/*
 * SettingsScanAdmin class
 * Builds up the setting page of the SCAN admin 
 * 
 * Add your settings here
 *
 * called by the index.php
 */

class ssa_scan_SettingsScanAdmin {

    public function __construct() {

        add_action('admin_init', array($this, 'addSettings'));

    }

    public function addSettings() {

        register_setting('scan_options', 'scan_options');

        add_settings_section('scan_main', 'Scan Settings', array($this, 'scan_settings_text'), 'scan');
        add_settings_field('pre_eindtekst', 'Eindresultaat intro', array($this, 'scan_pre_eindtekst'), 'scan', 'scan_main', array('label_for' => 'scan_options[pre_eindtekst]'));
        add_settings_field('post_eindtekst', 'Eindresultaat outro', array($this, 'scan_post_eindtekst'), 'scan', 'scan_main', array('label_for' => 'scan_options[post_eindtekst]'));
        add_settings_field('header_color', 'Kleur header', array($this, 'scan_header_color'), 'scan', 'scan_main', array('label_for' => 'scan_options[header_color]'));
        add_settings_field('text_color', 'Kleur header text', array($this, 'scan_text_color'), 'scan', 'scan_main', array('label_for' => 'scan_options[text_color]'));
    }

    /**
     *
     * Title text for the setting page
     *
     **/
    public function scan_settings_text() {

        echo '<br/><br/><p>Customize the behaviour of your Scans</p>';
    }

    /**
     *
     *
     *
     **/
    public function scan_pre_eindtekst() {

        $options = get_option('scan_options');
        echo "<textarea id='pre_eindtekst' name='scan_options[pre_eindtekst]' cols='50' rows='10'>{$options['pre_eindtekst']}</textarea>
        <span class='description'>Na de test krijgt de gebruiker een overzicht te zien. Vul hier een begeleidend schrijven</span> ";

    }
    /**
     *
     *
     *
     **/
    public function scan_post_eindtekst() {

        $options = get_option('scan_options');
        echo "<textarea id='post_eindtekst' name='scan_options[post_eindtekst]' cols='50' rows='10'>{$options['post_eindtekst']}</textarea>
        <span class='description'>Onder de resultaten wellicht nog een outro tekst of een link naar een bepaalde pagina</span> ";

    }
    /**
     *
     *
     *
     **/
    public function scan_header_color() {

        $options = get_option('scan_options');
        echo "<input id='header_color' name='scan_options[header_color]' type='text' value='{$options['header_color']}' class='color_picker'/>
        <span class='description'>Geef de header een eigen kleur (bijv: '5a759b')</span> ";

    }    
     /**
     *
     *
     *
     **/
    public function scan_text_color() {

        $options = get_option('scan_options');
        echo "<input id='text_color' name='scan_options[text_color]' type='text' value='{$options['text_color']}' class='color_picker'/>
        <span class='description'>Geef de header text een eigen kleur (bijv: '5a759b')</span> ";

    }   
    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }

}
