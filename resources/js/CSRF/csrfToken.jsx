export const getCsrfToken = () => {
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        return token.content;
    }
    return null;
};
