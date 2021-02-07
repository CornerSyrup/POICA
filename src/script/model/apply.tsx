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
