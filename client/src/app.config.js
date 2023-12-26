export default function initCfg(command, mode, ssrBuild) {
  const dev = command === 'serve';
  const host = 'silverstripe.lh';

  const buildAssetsDir = '../../../dist/ImageEditor/assets/';

  const ImageEditor = dev
    ? '../../../dist/ImageEditor/assets/ImageEditor/'
    : '';
  const ImageEditor_images = dev
    ? './images/'
    : `${buildAssetsDir}ImageEditor/images/`;

  return {
    host,
    certs: `/Applications/MAMP/Library/OpenSSL/certs/${host}`,

    sassAdditionalData: `
      $ImageEditor: '${ImageEditor}';
      $ImageEditor_images: '${ImageEditor_images}';
    `,
  };
}
