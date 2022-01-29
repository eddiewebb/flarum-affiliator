app.initializers.add('webbinaro/flarum-affiliator', () => {
  console.log('[webbinaro/flarum-affiliator] Hello, admin!');

  app.extensionData
  .for('webbinaro-affiliator')
  .registerSetting(
    {
      defaultValue: 'https://example.com?aff=123,https://example2.com?tracker=456',
      setting: 'webbinaro-affiliator.settings.aff.list', // This is the key the settings will be saved under in the settings table in the database.
      label: app.translator.trans('webbinaro-affiliator.admin.labels.affilate.host'), // The label to be shown letting the admin know what the setting does.
      help: app.translator.trans('webbinaro-affiliator.admin.labels.affilate.host_help'), // Optional help text where a longer explanation of the setting can go.
      type: 'text', // What type of setting this is, valid options are: boolean, text (or any other <input> tag type), and select. 
    },
    50 // Optional: Priority
  )
});
