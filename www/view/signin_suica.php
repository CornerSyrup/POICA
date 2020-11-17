<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in with Suica</title>
</head>

<body>
    <div>
        <h1>Some image or else here...</h1>
        <button id="test">test button</button>
    </div>
</body>
<!-- TODO: read https://qiita.com/odetarou/items/bcd65dbfd1f68735ac30 -->
<!-- TODO: read https://qiita.com/YasuakiNakazawa/items/3109df682af2a7032f8d -->
<!-- TODO: read https://qiita.com/saturday06/items/333fcdf5b3b8030c9b05 -->
<!-- TODO: read https://www.kenichi-odo.com/articles/2020_10_11_read-suica-by-webusb/ -->

<script>
    class FelicaReader {
        constructor() {
            console.info(`This Felica Reader is development version, please update when production.`);
        }

        ReadIdm() {
            return '1234567890123456';
        }
    }

    const reader = new FelicaReader();

    window.addEventListener("load", (ev) => {
        document.querySelector('#test')
            .addEventListener('click', ev => {
                let code = reader.ReadIdm();

                if (!code.length == 16) {
                    return;
                }

                fetch('/signin/suica/', {
                        method: 'POST',
                        cache: 'no-cache',
                        body: JSON.stringify({
                            idm: code
                        }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => console.info(data));
            });
    });
</script>

</html>