export function generateApplicationID(): Promise<string> {
    return crypto.subtle
        .digest("SHA-1", new TextEncoder().encode(new Date().toString()))
        .then((buffer) => {
            return Array.from(new Uint8Array(buffer))
                .map((b) => b.toString(16).padStart(2, "0"))
                .join("")
                .substr(0, 10);
        });
}

/**
 * Translate type id to string.
 * @param typeID As apply doc type enym
 */
export function typeIdTranslate(typeID: string) {
    let ret: string = "";
    switch (typeID) {
        case "doc":
            ret = "証明書発行願";
    }

    return ret;
}

export function status2Percent(status: number) {
    return status / 4 * 100;
}

export function status2String(status: number) {
    let ret: string = "";

    switch (status) {
        case 0:
            ret = "申し込み済み";
            break;
        case 1:
            ret = "処理待ち";
            break;
        case 2:
            ret = "処理中";
            break;
        case 3:
            ret = "完成";
            break;
        default:
            break;
    }

    return ret;
}
