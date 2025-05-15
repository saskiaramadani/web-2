import { Chart } from "@/components/ui/chart"
/**
 * Main JavaScript file for the application
 *
 * This file contains all the JavaScript functionality for the application.
 * It follows a modular approach and uses modern JavaScript features.
 */

// Initialize the application when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", () => {
  // Initialize Alpine.js custom components
  initAlpineComponents()

  // Initialize DataTables
  initDataTables()

  // Initialize charts if Chart.js is available
  if (typeof Chart !== "undefined") {
    initCharts()
  }

  // Initialize responsive tables
  initResponsiveTables()
})

/**
 * Initialize Alpine.js custom components and events
 */
function initAlpineComponents() {
  // Register custom Alpine.js components
  document.addEventListener("alpine:init", () => {
    // Sidebar toggle event
    window.addEventListener("toggle-sidebar", () => {
      const sidebar = document.querySelector(".sidebar")
      const backdrop = document.querySelector(".sidebar-backdrop")

      if (sidebar && backdrop) {
        sidebar.classList.add("sidebar-mobile-open")
        backdrop.classList.add("show")
      }
    })

    // Close sidebar event
    window.addEventListener("close-sidebar", () => {
      const sidebar = document.querySelector(".sidebar")
      const backdrop = document.querySelector(".sidebar-backdrop")

      if (sidebar && backdrop) {
        sidebar.classList.remove("sidebar-mobile-open")
        backdrop.classList.remove("show")
      }
    })
  })
}

/**
 * Initialize DataTables with custom styling and responsive features
 */
function initDataTables() {
  const dataTables = document.querySelectorAll(".datatable")

  if (dataTables.length > 0 && typeof $.fn.DataTable !== "undefined") {
    // Check if jQuery is loaded
    if (typeof jQuery == "undefined") {
      console.error("jQuery is not loaded. DataTables cannot be initialized.")
      return
    }

    dataTables.forEach((table) => {
      $(table).DataTable({
        responsive: true,
        language: {
          search: "Cari:",
          lengthMenu: "Tampilkan _MENU_ data per halaman",
          zeroRecords: "Tidak ada data yang ditemukan",
          info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
          infoEmpty: "Tidak ada data yang tersedia",
          infoFiltered: "(difilter dari _MAX_ total data)",
          paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: "Selanjutnya",
            previous: "Sebelumnya",
          },
        },
        dom:
          '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
          '<"row"<"col-sm-12"tr>>' +
          '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        initComplete: () => {
          // Add Bootstrap classes to DataTables elements
          $(".dataTables_length select").addClass("form-select form-select-sm")
          $(".dataTables_filter input").addClass("form-control form-control-sm")
          $(".dataTables_info").addClass("text-muted")
        },
      })
    })
  }
}

/**
 * Initialize charts
 */
function initCharts() {
  // Dashboard activity chart
  const activityChart = document.getElementById("activityChart")
  if (activityChart) {
    const ctx = activityChart.getContext("2d")

    // Get data from data attributes
    const labels = JSON.parse(activityChart.dataset.labels || "[]")
    const data = JSON.parse(activityChart.dataset.values || "[]")

    const backgroundColors = [
      "rgba(79, 70, 229, 0.7)",
      "rgba(16, 185, 129, 0.7)",
      "rgba(59, 130, 246, 0.7)",
      "rgba(245, 158, 11, 0.7)",
      "rgba(239, 68, 68, 0.7)",
      "rgba(99, 102, 241, 0.7)",
      "rgba(16, 185, 129, 0.7)",
      "rgba(245, 158, 11, 0.7)",
      "rgba(239, 68, 68, 0.7)",
      "rgba(99, 102, 241, 0.7)",
    ]

    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: labels,
        datasets: [
          {
            data: data,
            backgroundColor: backgroundColors,
            borderWidth: 1,
            borderColor: "#ffffff",
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              boxWidth: 12,
              padding: 15,
            },
          },
        },
        cutout: "70%",
      },
    })
  }
}

/**
 * Initialize responsive tables
 */
function initResponsiveTables() {
  const tables = document.querySelectorAll("table:not(.datatable)")

  tables.forEach((table) => {
    if (!table.parentElement.classList.contains("table-responsive")) {
      const wrapper = document.createElement("div")
      wrapper.classList.add("table-responsive")
      table.parentNode.insertBefore(wrapper, table)
      wrapper.appendChild(table)
    }
  })
}

/**
 * Format date to Indonesian format
 * @param {string} dateString - Date string in any format
 * @returns {string} - Formatted date string
 */
function formatDate(dateString) {
  if (!dateString) return "-"

  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
  }

  const date = new Date(dateString)
  return date.toLocaleDateString("id-ID", options)
}

/**
 * Format currency to Indonesian Rupiah
 * @param {number} amount - Amount to format
 * @returns {string} - Formatted currency string
 */
function formatCurrency(amount) {
  if (!amount && amount !== 0) return "-"

  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
  }).format(amount)
}

/**
 * Show loading spinner
 * @param {HTMLElement} element - Element to show loading in
 * @param {string} size - Size of the loader (sm, md, lg)
 * @returns {void}
 */
function showLoading(element, size = "md") {
  if (!element) return

  const sizeClass = size === "sm" ? "spinner-border-sm" : size === "lg" ? "spinner-border-lg" : ""

  const spinner = document.createElement("div")
  spinner.className = `spinner-border ${sizeClass} text-primary`
  spinner.setAttribute("role", "status")

  const span = document.createElement("span")
  span.className = "visually-hidden"
  span.textContent = "Loading..."

  spinner.appendChild(span)

  // Store original content
  element.dataset.originalContent = element.innerHTML
  element.innerHTML = ""
  element.appendChild(spinner)
  element.disabled = true
}

/**
 * Hide loading spinner and restore original content
 * @param {HTMLElement} element - Element to hide loading from
 * @returns {void}
 */
function hideLoading(element) {
  if (!element) return

  if (element.dataset.originalContent) {
    element.innerHTML = element.dataset.originalContent
    delete element.dataset.originalContent
    element.disabled = false
  }
}

/**
 * Confirm delete action
 * @param {string} message - Confirmation message
 * @returns {boolean} - True if confirmed, false otherwise
 */
function confirmDelete(message = "Apakah Anda yakin ingin menghapus data ini?") {
  return confirm(message)
}
