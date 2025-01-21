/**
 * Adds zeros before a number until the number has 2 digits.
 * @param num The number to pad
 * @returns The padded number
 */
export const pad = (num: number) => num.toString().padStart(2, '0')

/**
 * Converts a KebabCase or camelCase string to a regular string.
 * @param str The string to convert
 * @returns The converted string
 * @example
 * camelPad('WhatRYouDoing?') // What R You Doing?
 * camelPad('EYDLessThan5Days') // EYD Less Than 5 Days
 *
 */
export const camelPad = (str: string) => {
    return (
        str
            // Look for long acronyms and filter out the last letter
            .replace(/([A-Z]+)([A-Z][a-z])/g, ' $1 $2')
            // Look for lower-case letters followed by upper-case letters
            .replace(/([a-z\d])([A-Z])/g, '$1 $2')
            // Look for lower-case letters followed by numbers
            .replace(/([a-zA-Z])(\d)/g, '$1 $2')
            .replace(/^./, (str) => str.toUpperCase())
            // Remove any white space left around the word
            .trim()
    )
}
