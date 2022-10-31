export function buildQueryParams(ctx) {
    let str = `page=${ctx.currentPage}&perpage=${ctx.perPage}&sortby=${ctx.sortBy}&filter=${ctx.filter}&sortdesc=${ctx.sortDesc}`
    if (ctx.params) {
        for (var key in ctx.params)
        {
            if (!ctx.params.hasOwnProperty(key))
                continue;
            str += '&params['+key+']=' + ctx.params[key]
        }
    }
    return str
}
