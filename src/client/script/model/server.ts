export const HOST = window.location.host;

/**
 * Navigate to path in the same server.
 * @param path path section of URL, without leading slash.
 */
export function Navigate(path: string) {
    window.location.href = CreateURL(path);
}

/**
 * Create URL with path in the same server.
 * @param path path section of UEL, without leading slath.
 */
export function CreateURL(path: string) {
    return `https://${HOST}/${path}`;
}

export function Fetch(
    dest: string,
    method: string,
    body?: object
): Promise<any> {
    let init: RequestInit = {
        method: method.toUpperCase(),
    };

    if (body) {
        init.headers = { "Content-Type": "application/json" };
        init.body = JSON.stringify(body);
    }

    return fetch(dest, init).then((r) => r.json());
}
