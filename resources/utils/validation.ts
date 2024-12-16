export const isRequired = (v: string | number | boolean) =>
    (v !== undefined && v !== null && v !== '') || 'This field is required.'
