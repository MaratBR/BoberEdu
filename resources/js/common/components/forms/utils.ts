let index = 0;

export function inputId(): string {
    return 'I' + (index++) + '_' + Date.now();
}
