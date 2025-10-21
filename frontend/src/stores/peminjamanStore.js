import { defineStore } from "pinia";
import {ref} from "vue";

export const usePeminjamanStore = defineStore('peminjaman', () => {
  const daftarBarang = ref([])

  function tambahBarang(data) {
    daftarBarang.value.push(data)
  }

  return { daftarBarang, tambahBarang }
})
