export function getHash(source: string, algo: string): Promise<string> {
    return crypto.subtle
        .digest(algo, new TextEncoder().encode(source))
        .then((buffer: ArrayBuffer) => {
            return Array.from(new Uint8Array(buffer))
                .map((hex) => hex.toString(16).padStart(2, "0"))
                .join("");
        });
}

export function getSHA1(source: string): Promise<string> {
    return getHash(source, "SHA-1");
}
export function getSHA256(source: string): Promise<string> {
    return getHash(source, "SHA-256");
}
