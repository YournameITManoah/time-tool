/**
 * Removes seconds from a time string when seconds are zero.
 * @param time The time string
 * @returns The formatted time string
 */
export const formatTime = (time?: string) =>
    time?.replace(/(\d{2}:\d{2}):00/g, '$1')
