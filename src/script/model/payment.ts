export function buildCurrencyAmount(
    amount: number,
    currency: string = "JPY"
): PaymentCurrencyAmount {
    return {
        currency: currency,
        value: amount.toString(),
    };
}
