export const validateError = (data: any) => {
    const validate: { [key: string]: { text: any; show: boolean } } = {};
    Object.keys(data).forEach((key) => {
        if (Array.isArray(data[key])) {
            validate[key] = { text: data[key][0], show: true };
        } else {
            validate[key] = { text: data[key], show: true };
        }
    });
    return validate;
};

export const getQueryString = () => {
    const url = new URL(window.location.href);
    const searchParams = new URLSearchParams(url.search);
    const params: { [key: string]: string } = {};
    for (const param of searchParams) {
        params[param[0]] = param[1];
    }
    return params;
};
