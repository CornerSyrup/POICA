export async function sleep(msec: number) {
  return new Promise((resolve) => setTimeout(resolve, msec));
}

async function send(device: any, data: Array<number>) {
  await device.transferOut(2, new Uint8Array(data));
  await sleep(10);
}

async function receive(device: any, len: number) {
  let data = await device.transferIn(1, len);
  await sleep(10);
  let arr = [];
  for (let i = data.data.byteOffset; i < data.data.byteLength; i++) {
    arr.push(data.data.getUint8(i));
  }
  return arr;
}

/**
 * Session with NFC card reader.
 *
 * @param device USB based NFC card reader
 * @returns string Idm code as string in 16 char.
 * @throws error unable to obtain idm with usb device with spec length.
 */
export async function session(device: any) {
  //#region device com
  await send(device, [0x00, 0x00, 0xff, 0x00, 0xff, 0x00]);
  await send(device, [
    0x00,
    0x00,
    0xff,
    0xff,
    0xff,
    0x03,
    0x00,
    0xfd,
    0xd6,
    0x2a,
    0x01,
    0xff,
    0x00,
  ]);
  await receive(device, 6);
  await receive(device, 13);
  await send(device, [
    0x00,
    0x00,
    0xff,
    0xff,
    0xff,
    0x03,
    0x00,
    0xfd,
    0xd6,
    0x06,
    0x00,
    0x24,
    0x00,
  ]);
  await receive(device, 6);
  await receive(device, 13);
  await send(device, [
    0x00,
    0x00,
    0xff,
    0xff,
    0xff,
    0x03,
    0x00,
    0xfd,
    0xd6,
    0x06,
    0x00,
    0x24,
    0x00,
  ]);
  await receive(device, 6);
  await receive(device, 13);
  await send(device, [
    0x00,
    0x00,
    0xff,
    0xff,
    0xff,
    0x06,
    0x00,
    0xfa,
    0xd6,
    0x00,
    0x01,
    0x01,
    0x0f,
    0x01,
    0x18,
    0x00,
  ]);
  await receive(device, 6);
  await receive(device, 13);
  await send(device, [
    0x00,
    0x00,
    0xff,
    0xff,
    0xff,
    0x28,
    0x00,
    0xd8,
    0xd6,
    0x02,
    0x00,
    0x18,
    0x01,
    0x01,
    0x02,
    0x01,
    0x03,
    0x00,
    0x04,
    0x00,
    0x05,
    0x00,
    0x06,
    0x00,
    0x07,
    0x08,
    0x08,
    0x00,
    0x09,
    0x00,
    0x0a,
    0x00,
    0x0b,
    0x00,
    0x0c,
    0x00,
    0x0e,
    0x04,
    0x0f,
    0x00,
    0x10,
    0x00,
    0x11,
    0x00,
    0x12,
    0x00,
    0x13,
    0x06,
    0x4b,
    0x00,
  ]);
  await receive(device, 6);
  await receive(device, 13);
  await send(device, [
    0x00,
    0x00,
    0xff,
    0xff,
    0xff,
    0x04,
    0x00,
    0xfc,
    0xd6,
    0x02,
    0x00,
    0x18,
    0x10,
    0x00,
  ]);
  await receive(device, 6);
  await receive(device, 13);
  await send(device, [
    0x00,
    0x00,
    0xff,
    0xff,
    0xff,
    0x0a,
    0x00,
    0xf6,
    0xd6,
    0x04,
    0x6e,
    0x00,
    0x06,
    0x00,
    0xff,
    0xff,
    0x01,
    0x00,
    0xb3,
    0x00,
  ]);
  await receive(device, 6);
  //#endregion

  let idm: Array<number> = (await receive(device, 37)).slice(17, 25);

  // idm should be 8 byte or no reading
  if (idm.length != 8 && idm.length != 0) {
    throw "Fail to obtain idm from device";
  }

  // conv bin into hex
  return idm
    .map((val) => (val < 16 ? 0 + val.toString(16) : val.toString(16)))
    .join("");
}
