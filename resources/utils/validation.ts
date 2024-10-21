export const isRequired = (v: string) =>
    !!v?.length || 'This field is required.'
