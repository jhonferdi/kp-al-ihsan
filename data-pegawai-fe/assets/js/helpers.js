export function filterBySearch(items, search, excludedKeys = []) {
  const searchQuery = search && search.toLowerCase()
 
  let newItems = items
  if (searchQuery) {
    newItems = newItems.filter((row) => {
      return Object.keys(row).some((key) => {
        return String(row[key]).toLowerCase().indexOf(searchQuery) > -1 && !excludedKeys.includes(key)
      })
    })
    return newItems
  }
  return items
}