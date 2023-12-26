export default function initCfg(command, mode, ssrBuild) {

  const dev = command === 'serve';
  const host = 'silverstripe.lh';

  const buildAssetsDir = '../../../dist/ImageSettings/assets/'

  const ImageSettings = dev ? '../../../dist/ImageSettings/assets/ImageSettings/' : ''
  const ImageSettings_images = dev ? './images/' : (buildAssetsDir + 'ImageSettings/images/');

  return {

    host: host,
    certs: '/Applications/MAMP/Library/OpenSSL/certs/' + host,

    sassAdditionalData: `
      $ImageSettings: '${ImageSettings}';
      $ImageSettings_images: '${ImageSettings_images}';
    `,
  }
}
