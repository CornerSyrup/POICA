# Report of utilizing Web Usb

## Precondition

- Using NFC Port PaSoRi RC-S380 by Sony.
- Use Chromium base browser.

## Reference

- [WebUSBでFeliCaの一意なIDであるIDmを読む - qiita](https://qiita.com/saturday06/items/333fcdf5b3b8030c9b05)
- [Web USBでPaSoRiを扱えるOSをまとめてみた - qiita](https://qiita.com/attakei/items/95cb9b53fa3ede942b3e)
- [（Ubuntuで）WebUSBでPasoriを扱ってみる - qiita](https://qiita.com/frameair/items/596724fc2f3438ea7925)
- [Webusb: Access Denied trying to open printer on Windows - stackoverflow](https://stackoverflow.com/questions/47143148/webusb-access-denied-trying-to-open-printer-on-windows)
- [SmartCard reader “Access denied” while claiming interface with Webusb on chrome - stackoverflow](https://stackoverflow.com/questions/46179569/smartcard-reader-access-denied-while-claiming-interface-with-webusb-on-chrome/46186495#46186495)
- [Access Denied in device.open : Webusb - GitHub issue](https://github.com/WICG/webusb/issues/184)

## Condition

### OS

#### Windows

Error might face:

- Device not found
- Access Denied

Resolve:

utilize WinUsb with Zadig.

#### macOS

No problem, can read IDm.

#### Android

- Fire OS with Amazon Silk (browser), OK.
- Fire OS with Google Chrome, OK.
- Android with Google Chrome, OK.

### Browser

#### Chromium (Google Chrome/Misoft Edge)

Resolve:

Enable flag:

- Enable new USB backend (#new-usb-backend)

### Device

[USB device interface has been blocked](https://stackoverflow.com/questions/54289929/usb-device-interface-has-been-blocked)

the following type of devices (interfaces) were prevented to access for security reason, on Chromium based browser.

- audio
- HID
- mass storage
- smart card
- video
- audio/video
- wireless controller

## Result

result of IDm reading from Suica, Mobile Suica and Octopus

- 012b07018516fe
- 01011001841ae9
- 0139a4bf3287e6
- 01399768c287e6
- 010103129d1ba8
