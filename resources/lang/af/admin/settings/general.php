<?php

return array(
    'ad'				        => 'Aktiewe gids',
    'ad_domain'				    => 'Active Directory-domein',
    'ad_domain_help'			=> 'Dit is soms dieselfde as jou e-pos domein, maar nie altyd nie.',
    'ad_append_domain_label'    => 'Append domain name',
    'ad_append_domain'          => 'Append domain name to username field',
    'ad_append_domain_help'     => 'User isn\'t required to write "username@domain.local", they can just type "username".' ,
    'admin_cc_email'            => 'CC Email',
    'admin_cc_email_help'       => 'If you would like to send a copy of checkin/checkout emails that are sent to users to an additional email account, enter it here. Otherwise leave this field blank.',
    'is_ad'				        => 'Dit is \'n Active Directory-bediener',
    'alert_email'				=> 'Stuur kennisgewings aan',
    'alerts_enabled'			=> 'Alerts aangeskakel',
    'alert_interval'			=> 'Uitgaande Alert Drempel (in dae)',
    'alert_inv_threshold'		=> 'Voorraadwaarskuwingsdrempel',
    'asset_ids'					=> 'Bate ID\'s',
    'audit_interval'            => 'Ouditinterval',
    'audit_interval_help'       => 'As u gereeld u bates fisies moet kontroleer, vul die interval in maande in.',
    'audit_warning_days'        => 'Oudit Waarskuwing Drempel',
    'audit_warning_days_help'   => 'Hoeveel dae vooruit moet ons u waarsku wanneer bates verskuldig is vir ouditering?',
    'auto_increment_assets'		=> 'Genereer outomaties inkrementele bate-ID\'s',
    'auto_increment_prefix'		=> 'Voorvoegsel (opsioneel)',
    'auto_incrementing_help'    => 'Aktiveer outomaties inkrementering van bate ID\'s om dit te stel',
    'backups'					=> 'rugsteun',
    'barcode_settings'			=> 'Barcode-instellings',
    'confirm_purge'			    => 'Bevestig skoonmaak',
    'confirm_purge_help'		=> 'Enter the text "DELETE" in the box below to purge your deleted records. This action cannot be undone and will PERMANENTLY delete all soft-deleted items and users. (You should make a backup first, just to be safe.)',
    'custom_css'				=> 'Aangepaste CSS',
    'custom_css_help'			=> 'Voer enige aangepaste CSS-oortredings in wat u graag wil gebruik. Moenie die &lt;style&gt;&lt;/style&gt;-etikette insluit nie.',
    'custom_forgot_pass_url'	=> 'Custom Password Reset URL',
    'custom_forgot_pass_url_help'	=> 'This replaces the built-in forgotten password URL on the login screen, useful to direct people to internal or hosted LDAP password reset functionality. It will effectively disable local user forgotten password functionality.',
    'dashboard_message'			=> 'Dashboard Message',
    'dashboard_message_help'	=> 'This text will appear on the dashboard for anyone with permission to view the dashboard.',
    'default_currency'  		=> 'Verstek Geld',
    'default_eula_text'			=> 'Standaard EULA',
    'default_language'			=> 'Verstek taal',
    'default_eula_help_text'	=> 'U kan ook aangepaste EULA\'s aan spesifieke batekategorieë assosieer.',
    'display_asset_name'        => 'Wys bate naam',
    'display_checkout_date'     => 'Vertoon Checkout Date',
    'display_eol'               => 'Wys EOL in tabelweergawe',
    'display_qr'                => 'Vertoon vierkante kodes',
    'display_alt_barcode'		=> 'Wys 1D strepieskode',
    'email_logo'                => 'Email Logo',
    'barcode_type'				=> '2D Barcode Type',
    'alt_barcode_type'			=> '1D barcode tipe',
    'email_logo_size'       => 'Square logos in email look best. ',
    'eula_settings'				=> 'EULA-instellings',
    'eula_markdown'				=> 'Hierdie EULA laat <a href="https://help.github.com/articles/github-flavored-markdown/">Github-geurde markdown</a> toe.',
    'favicon'                   => 'Favicon',
    'favicon_format'            => 'Accepted filetypes are ico, png, and gif. Other image formats may not work in all browsers.',
    'favicon_size'          => 'Favicons should be square images, 16x16 pixels.',
    'footer_text'               => 'Additional Footer Text ',
    'footer_text_help'          => 'This text will appear in the right-side footer. Links are allowed using <a href="https://help.github.com/articles/github-flavored-markdown/">Github flavored markdown</a>. Line breaks, headers, images, etc may result in unpredictable results.',
    'general_settings'			=> 'Algemene instellings',
    'generate_backup'			=> 'Genereer rugsteun',
    'header_color'              => 'Opskrif Kleur',
    'info'                      => 'Met hierdie instellings kan u sekere aspekte van u installasie aanpas.',
    'label_logo'                => 'Label Logo',
    'label_logo_size'           => 'Square logos look best - will be displayed in the top right of each asset label. ',
    'laravel'                   => 'Laravel Weergawe',
    'ldap_enabled'              => 'LDAP aangeskakel',
    'ldap_integration'          => 'LDAP-integrasie',
    'ldap_settings'             => 'LDAP-instellings',
    'ldap_login_test_help'      => 'Enter a valid LDAP username and password from the base DN you specified above to test whether your LDAP login is configured correctly. YOU MUST SAVE YOUR UPDATED LDAP SETTINGS FIRST.',
    'ldap_login_sync_help'      => 'This only tests that LDAP can sync correctly. If your LDAP Authentication query is not correct, users may still not be able to login. YOU MUST SAVE YOUR UPDATED LDAP SETTINGS FIRST.',
    'ldap_server'               => 'LDAP-bediener',
    'ldap_server_help'          => 'Dit moet begin met ldap: // (vir unencrypted of TLS) of ldaps: // (vir SSL)',
    'ldap_server_cert'			=> 'LDAP SSL-sertifikaat-validering',
    'ldap_server_cert_ignore'	=> 'Laat ongeldige SSL-sertifikaat toe',
    'ldap_server_cert_help'		=> 'Kies hierdie boks as u \'n self-ondertekende SSL-sertifikaat gebruik en graag \'n ongeldige SSL-sertifikaat aanvaar.',
    'ldap_tls'                  => 'Gebruik TLS',
    'ldap_tls_help'             => 'Dit moet slegs nagegaan word as u STARTTLS op u LDAP-bediener uitvoer.',
    'ldap_uname'                => 'LDAP Bind Gebruikersnaam',
    'ldap_pword'                => 'LDAP-koppel wagwoord',
    'ldap_basedn'               => 'Base Bind DN',
    'ldap_filter'               => 'LDAP Filter',
    'ldap_pw_sync'              => 'LDAP-wagwoordsynkronisering',
    'ldap_pw_sync_help'         => 'Verwyder hierdie vinkje as u nie LDAP-wagwoorde wil laat sinkroniseer met plaaslike wagwoorde nie. As u hierdie opsie uitskakel, beteken dit dat u gebruikers dalk nie kan aanmeld as u LDAP-bediener om een ​​of ander rede onbereikbaar is nie.',
    'ldap_username_field'       => 'Gebruikernaam',
    'ldap_lname_field'          => 'Van',
    'ldap_fname_field'          => 'LDAP Voornaam',
    'ldap_auth_filter_query'    => 'LDAP-verifikasie navraag',
    'ldap_version'              => 'LDAP-weergawe',
    'ldap_active_flag'          => 'LDAP-aktiewe vlag',
    'ldap_activated_flag_help'  => 'This flag is used to determine whether a user can login to Snipe-IT and does not affect the ability to check items in or out to them.',
    'ldap_emp_num'              => 'LDAP Werknemersnommer',
    'ldap_email'                => 'LDAP-e-pos',
    'license'                  => 'Software License',
    'load_remote_text'          => 'Remote Scripts',
    'load_remote_help_text'		=> 'Hierdie Snipe-IT installasie kan skrifte van die buitewêreld laai.',
    'login_note'                => 'Login Nota',
    'login_note_help'           => 'Voeg opsioneel \'n paar sinne op jou aanmeldskerm, byvoorbeeld om mense te help wat \'n verlore of gesteelde toestel gevind het. Hierdie veld aanvaar <a href="https://help.github.com/articles/github-flavored-markdown/">Gitub-gegeurde markdown</a>',
    'login_remote_user_text'    => 'Remote User login options',
    'login_remote_user_enabled_text' => 'Enable Login with Remote User Header',
    'login_remote_user_enabled_help' => 'This option enables Authentication via the REMOTE_USER header according to the "Common Gateway Interface (rfc3875)"',
    'login_common_disabled_text' => 'Disable other authentication mechanisms',
    'login_common_disabled_help' => 'This option disables other authentication mechanisms. Just enable this option if you are sure that your REMOTE_USER login is already working',
    'login_remote_user_custom_logout_url_text' => 'Custom logout URL',
    'login_remote_user_custom_logout_url_help' => 'If a url is provided here, users will get redirected to this URL after the user logs out of Snipe-IT. This is useful to close the user sessions of your Authentication provider correctly.',
    'login_remote_user_header_name_text' => 'Custom user name header',
    'login_remote_user_header_name_help' => 'Use the specified header instead of REMOTE_USER',
    'logo'                    	=> 'logo',
    'logo_print_assets'         => 'Use in Print',
    'logo_print_assets_help'    => 'Use branding on printable asset lists ',
    'full_multiple_companies_support_help_text' => 'Beperking van gebruikers (insluitend administrateurs) wat aan maatskappye toegewys is aan hul maatskappy se bates.',
    'full_multiple_companies_support_text' => 'Volledige Veelvuldige Maatskappye Ondersteuning',
    'show_in_model_list'   => 'Show in Model Dropdowns',
    'optional'					=> 'opsioneel',
    'per_page'                  => 'Resultate per bladsy',
    'php'                       => 'PHP weergawe',
    'php_gd_info'               => 'Jy moet php-gd installeer om QR-kodes te vertoon, sien installeringsinstruksies.',
    'php_gd_warning'            => 'PHP Image Processing en GD plugin is NIE geïnstalleer nie.',
    'pwd_secure_complexity'     => 'Wagwoord Kompleksiteit',
    'pwd_secure_complexity_help' => 'Kies watter wagwoord kompleksiteit reëls jy wil afdwing.',
    'pwd_secure_min'            => 'Wagwoord minimum karakters',
    'pwd_secure_min_help'       => 'Minimum permitted value is 8',
    'pwd_secure_uncommon'       => 'Voorkom algemene wagwoorde',
    'pwd_secure_uncommon_help'  => 'Dit sal gebruikers nie toelaat om algemene wagwoorde te gebruik van die top 10,000 wagwoorde wat in oortredings gerapporteer is nie.',
    'qr_help'                   => 'Aktiveer QR-kodes eers om dit te stel',
    'qr_text'                   => 'QR Kode Teks',
    'saml_enabled'              => 'SAML enabled',
    'saml_integration'          => 'SAML Integration',
    'saml_sp_entityid'          => 'Entity ID',
    'saml_sp_acs_url'           => 'Assertion Consumer Service (ACS) URL',
    'saml_sp_sls_url'           => 'Single Logout Service (SLS) URL',
    'saml_sp_x509cert'          => 'Public Certificate',
    'saml_idp_metadata'         => 'SAML IdP Metadata',
    'saml_idp_metadata_help'    => 'You can specify the IdP metadata using a URL or XML file.',
    'saml_attr_mapping_username' => 'Attribute Mapping - Username',
    'saml_attr_mapping_username_help' => 'NameID will be used if attribute mapping is unspecified or invalid.',
    'saml_forcelogin_label'     => 'SAML Force Login',
    'saml_forcelogin'           => 'Make SAML the primary login',
    'saml_forcelogin_help'      => 'You can use \'/login?nosaml\' to get to the normal login page.',
    'saml_slo_label'            => 'SAML Single Log Out',
    'saml_slo'                  => 'Send a LogoutRequest to IdP on Logout',
    'saml_slo_help'             => 'This will cause the user to be first redirected to the IdP on logout. Leave unchecked if the IdP doesn\'t correctly support SP-initiated SAML SLO.',
    'saml_custom_settings'      => 'SAML Custom Settings',
    'saml_custom_settings_help' => 'You can specify additional settings to the onelogin/php-saml library. Use at your own risk.',
    'setting'                   => 'omgewing',
    'settings'                  => 'instellings',
    'show_alerts_in_menu'       => 'Show alerts in top menu',
    'show_archived_in_list'     => 'Archived Assets',
    'show_archived_in_list_text'     => 'Show archived assets in the "all assets" listing',
    'show_assigned_assets'      => 'Show assets assigned to assets',
    'show_assigned_assets_help' => 'Display assets which were assigned to the other assets in View User -> Assets, View User -> Info -> Print All Assigned and in Account -> View Assigned Assets.',
    'show_images_in_email'     => 'Show images in emails',
    'show_images_in_email_help'   => 'Uncheck this box if your Snipe-IT installation is behind a VPN or closed network and users outside the network will not be able to load images served from this installation in their emails.',
    'site_name'                 => 'Site Naam',
    'slack_botname'             => 'Slack Botname',
    'slack_channel'             => 'Slack Channel',
    'slack_endpoint'            => 'Slack Endpoint',
    'slack_integration'         => 'Slack Settings',
    'slack_integration_help'    => 'Slack integration is optional, however the endpoint and channel are required if you wish to use it. To configure Slack integration, you must first <a href=":slack_link" target="_new" rel="noopener">create an incoming webhook</a> on your Slack account. Click on the <strong>Test Slack Integration</strong> button to confirm your settings are correct before saving. ',
    'slack_integration_help_button'    => 'Once you have saved your Slack information, a test button will appear.',
    'slack_test_help'           => 'Test whether your Slack integration is configured correctly. YOU MUST SAVE YOUR UPDATED SLACK SETTINGS FIRST.',
    'snipe_version'  			=> 'Snipe-IT-weergawe',
    'support_footer'            => 'Support Footer Links ',
    'support_footer_help'       => 'Specify who sees the links to the Snipe-IT Support info and Users Manual',
    'version_footer'            => 'Version in Footer ',
    'version_footer_help'       => 'Specify who sees the Snipe-IT version and build number.',
    'system'                    => 'Stelselinligting',
    'update'                    => 'Opdateer instellings',
    'value'                     => 'waarde',
    'brand'                     => 'Handelsmerk',
    'web_brand'                 => 'Web Branding Type',
    'about_settings_title'      => 'Oor instellings',
    'about_settings_text'       => 'Met hierdie instellings kan u sekere aspekte van u installasie aanpas.',
    'labels_per_page'           => 'Etikette per bladsy',
    'label_dimensions'          => 'Etiketafmetings (duim)',
    'next_auto_tag_base'        => 'Volgende outomatiese inkrement',
    'page_padding'              => 'Bladsy marges (duim)',
    'privacy_policy_link'       => 'Link to Privacy Policy',
    'privacy_policy'            => 'Privacy Policy',
    'privacy_policy_link_help'  => 'If a url is included here, a link to your privacy policy will be included in the app footer and in any emails that the system sends out, in compliance with GDPR. ',
    'purge'                     => 'Verwyder verwyderde rekords',
    'labels_display_bgutter'    => 'Etiket onderkant goot',
    'labels_display_sgutter'    => 'Label side goot',
    'labels_fontsize'           => 'Etiket lettergrootte',
    'labels_pagewidth'          => 'Label vel breedte',
    'labels_pageheight'         => 'Etiketbladhoogte',
    'label_gutters'        => 'Etiket spasiëring (duim)',
    'page_dimensions'        => 'Bladsy dimensies (duim)',
    'label_fields'          => 'Merk sigbare velde',
    'inches'        => 'duim',
    'width_w'        => 'w',
    'height_h'        => 'h',
    'show_url_in_emails'                => 'Skakel na Snipe-IT in e-posse',
    'show_url_in_emails_help_text'      => 'Verwyder hierdie vinkje as u nie wil terugkoppel na u Snipe-IT-installasie in u e-posvoetboks nie. Nuttig as die meeste van jou gebruikers nooit ingeteken het nie.',
    'text_pt'        => 'pt',
    'thumbnail_max_h'   => 'Maksimum miniatuurhoogte',
    'thumbnail_max_h_help'   => 'Maksimum hoogte in pixels wat duimnaels mag vertoon in die lysinskrywing. Min 25, maksimum 500.',
    'two_factor'        => 'Twee faktor verifikasie',
    'two_factor_secret'        => 'Twee-faktor kode',
    'two_factor_enrollment'        => 'Twee-faktorinskrywing',
    'two_factor_enabled_text'        => 'Aktiveer twee faktore',
    'two_factor_reset'        => 'Herstel twee-faktor geheim',
    'two_factor_reset_help'        => 'Dit sal die gebruiker dwing om hul toestel weer met Google Authenticator in te skryf. Dit kan handig wees as hul toestel wat tans ingeskryf is, verlore of gesteel is.',
    'two_factor_reset_success'          => 'Twee faktor toestel suksesvol herstel',
    'two_factor_reset_error'          => 'Twee faktor toestel herstel het misluk',
    'two_factor_enabled_warning'        => 'As jy twee faktore aktiveer as dit nie tans geaktiveer is nie, sal dit jou dadelik dwing om te verifieer met \'n Google Auth-ingeskrewe toestel. Jy sal die vermoë hê om jou toestel in te skryf as een nie tans ingeskryf is nie.',
    'two_factor_enabled_help'        => 'Dit sal twee-faktor-verifikasie met behulp van Google Authenticator aanskakel.',
    'two_factor_optional'        => 'Selektief (Gebruikers kan aktiveer of deaktiveer indien toegelaat)',
    'two_factor_required'        => 'Benodig vir alle gebruikers',
    'two_factor_disabled'        => 'gestremde',
    'two_factor_enter_code'	=> 'Voer twee-faktor kode in',
    'two_factor_config_complete'	=> 'Dien kode in',
    'two_factor_enabled_edit_not_allowed' => 'Jou administrateur laat jou nie toe om hierdie instelling te wysig nie.',
    'two_factor_enrollment_text'	=> "Twee faktor verifikasie is nodig, maar jou toestel is nog nie ingeskryf nie. Maak jou Google Authenticator-program oop en scan die QR-kode hieronder om jou toestel in te skryf. Sodra jy jou toestel ingeskryf het, voer die kode hieronder in",
    'require_accept_signature'      => 'Vereis Handtekening',
    'require_accept_signature_help_text'      => 'As u hierdie kenmerk aanskakel, sal gebruikers fisies moet afmeld wanneer hulle \'n bate aanvaar.',
    'left'        => 'links',
    'right'        => 'reg',
    'top'        => 'Top',
    'bottom'        => 'onderkant',
    'vertical'        => 'vertikale',
    'horizontal'        => 'horisontale',
    'unique_serial'                => 'Unique serial numbers',
    'unique_serial_help_text'                => 'Checking this box will enforce a uniqueness constraint on asset serials',
    'zerofill_count'        => 'Lengte van bate-etikette, insluitend zerofill',
    'username_format_help'   => 'This setting will only be used by the import process if a username is not provided and we have to generate a username for you.',
);
