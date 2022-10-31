/**
 * Get cookie from request.
 *
 * @param  {Object} req
 * @param  {String} key
 * @return {String|undefined}
 */
export function cookieFromRequest (req, key) {
  if (!req.headers.cookie) {
    return
  }

  const cookie = req.headers.cookie.split(';').find(
    c => c.trim().startsWith(`${key}=`)
  )

  if (cookie) {
    return cookie.split('=')[1]
  }
}

/**
 * https://router.vuejs.org/en/advanced/scroll-behavior.html
 */
export function scrollBehavior (to, from, savedPosition) {
  if (savedPosition) {
    return savedPosition
  }

  let position = {}

  if (to.matched.length < 2) {
    position = { x: 0, y: 0 }
  } else if (to.matched.some(r => r.components.default.options.scrollToTop)) {
    position = { x: 0, y: 0 }
  } if (to.hash) {
    position = { selector: to.hash }
  }

  return position
}

export function pluck(array, key) {
  return array.map(o => o[key]);
}

export function parseParamsObject (params) {
  if (!params) return {}
  let p = params.split('*')
  const px = {}
  for (var i = 0; i < p.length; i++) {
    const pa = p[i].split(/\^(.+)/)
    px[pa[0]] = pa[1]
  }
  return px
}
export function parseParams (params) {
  if (!params) return {}
  let p = params.split('|')
  const px = {}
  for (var i = 0; i < p.length; i++) {
    const pa = p[i].split(/:(.+)/)
    if (pa[1] && pa[1].indexOf('^') > -1) {
      pa[1] = parseParamsObject(pa[1])
    }
    px[pa[0]] = pa[1]
  }
  return px
}

export function stringifyObject(params) {
  let arr = Object.entries(params)
  let strs = []
  for (var i = 0; i < arr.length; i++) {
    if (typeof arr[i][1] == 'object') {
      arr[i][1] = stringifyObject(arr[i][1])
    }
    strs.push(arr[i].join('^'))
  }
  return strs.join('*')
}
export function stringifyParams(params) {
  let arr = Object.entries(params)
  let strs = []
  for (var i = 0; i < arr.length; i++) {
    if (typeof arr[i][1] == 'object') {
      arr[i][1] = stringifyObject(arr[i][1])
    }
    strs.push(arr[i].join(':'))
  }
  return encodeURIComponent(strs.join('|'))
}
