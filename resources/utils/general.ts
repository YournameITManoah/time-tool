/**
 * Adds zeros before a number until the number has 2 digits.
 * @param num The number to pad
 * @returns The padded number
 */
export const pad = (num: number) => num.toString().padStart(2, '0')
