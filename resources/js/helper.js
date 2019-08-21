export function parseMoney(value) {
    if (!value) {
        return 0;
    }
    if (typeof value === "string") {
        let result = value.replace(/(Bs|\s+)/gi, ``);
        result = result.replace(/,/g, ``);
        return parseFloat(result);
    }
    return typeof value === "number" ? parseFl(value) : alert("error: parseMoney");
}
