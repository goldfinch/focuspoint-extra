export default function initCfg(command, mode, ssrBuild) {

  const dev = command === 'serve';
  const host = 'silverstripe.lh';

  const buildAssetsDir = '../../../dist/focuspointextra/assets/'

  const focuspointextra = dev ? '../../../dist/focuspointextra/assets/focuspointextra/' : ''
  const focuspointextra_images = dev ? './images/' : (buildAssetsDir + 'focuspointextra/images/');

  return {

    host: host,
    certs: '/Applications/MAMP/Library/OpenSSL/certs/' + host,

    sassAdditionalData: `
      $focuspointextra: '${focuspointextra}';
      $focuspointextra_images: '${focuspointextra_images}';
    `,
  }
}
