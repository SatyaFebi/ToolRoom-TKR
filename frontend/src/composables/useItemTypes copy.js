import { computed } from 'vue'
import { useItemTypesStore } from '@/stores/itemTypes'

export default function useItemTypes() {
  const store = useItemTypesStore()

  return {
    itemTypes: computed(() => store.itemTypes),
    loading: computed(() => store.loading),
    error: computed(() => store.error),
    fetchItemTypes: store.fetchItemTypes,
    createItemType: store.createItemType,
    updateItemType: store.updateItemType,
    deleteItemType: store.deleteItemType,
  }
}
