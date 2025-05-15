import { Chart } from "@/components/ui/chart"
/**
 * Main JavaScript file for the application
 *
 * This file contains all the JavaScript functionality for the application.
 * It follows a modular approach and uses modern JavaScript features.
 */

// Initialize the application when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", () => {
  // Initialize tooltips
  initTooltips()

  // Initialize popovers
  initPopovers()

  // Initialize sidebar
  initSidebar()

  // Initialize active navigation
  initActiveNavigation()

  // Initialize form validation
  initFormValidation()

  // Initialize DataTables
  initDataTables()

  // Initialize charts if Chart.js is available
  if (typeof Chart !== "undefined") {
    initCharts()
  }

  // Initialize alerts auto-dismiss
  initAlertsDismiss()

  // Initialize password toggle
  initPasswordToggle()

  // Initialize responsive tables
  initResponsiveTables()

  // Initialize dark mode toggle if it exists
  const darkModeToggle = document.getElementById("darkModeToggle")
  if (darkModeToggle) {
    initDarkMode()
  }
})

/**
 * Initialize Bootstrap tooltips
 */
function initTooltips() {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.map(
    (tooltipTriggerEl) =>
      new bootstrap.Tooltip(tooltipTriggerEl, {
        delay: { show: 300, hide: 100 },
      }),
  )
}

/**
 * Initialize Bootstrap popovers
 */
function initPopovers() {
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  popoverTriggerList.map((popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl))
}

/**
 * Initialize sidebar functionality
 */
function initSidebar() {
  const sidebarToggle = document.getElementById("sidebarToggle")
  const mobileSidebarToggle = document.getElementById("mobileSidebarToggle")
  const sidebar = document.querySelector(".sidebar")
  const content = document.querySelector(".content")

  // Check if sidebar elements exist
  if (!sidebar || !content) return

  // Mobile sidebar toggle
  if (mobileSidebarToggle) {
    mobileSidebarToggle.addEventListener("click", () => {
      sidebar.classList.toggle("show")
    })

    // Close sidebar when clicking outside on mobile
    document.addEventListener("click", (event) => {
      if (
        window.innerWidth < 992 &&
        sidebar.classList.contains("show") &&
        !sidebar.contains(event.target) &&
        event.target !== mobileSidebarToggle
      ) {
        sidebar.classList.remove("show")
      }
    })
  }

  // Handle window resize
  window.addEventListener("resize", () => {
    if (window.innerWidth >= 992) {
      sidebar.classList.remove("show")
    }
  })
}

/**
 * Initialize active navigation based on current URL
 */
function initActiveNavigation() {
  const currentLocation = window.location.pathname
  const navLinks = document.querySelectorAll(".sidebar-nav-link")

  navLinks.forEach((link) => {
    const href = link.getAttribute("href")
    if (href && currentLocation.includes(href) && href !== "/" && href !== "#") {
      link.classList.add("active")

      // If it's in a section, expand the section
      const section = link.closest(".sidebar-section")
      if (section) {
        const sectionContent = section.querySelector(".sidebar-section-content")
        if (sectionContent && sectionContent.classList.contains("collapse")) {
          const bsCollapse = new bootstrap.Collapse(sectionContent, { toggle: false })
          bsCollapse.show()
        }
      }
    }
  })
}

/**
 * Initialize form validation
 */
function initFormValidation() {
  const forms = document.querySelectorAll(".needs-validation")

  Array.from(forms).forEach((form) => {
    form.addEventListener(
      "submit",
      (event) => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add("was-validated")
      },
      false,
    )

    // Real-time validation for password confirmation
    const password = form.querySelector('input[name="password"]')
    const confirmPassword = form.querySelector('input[name="confirm_password"]')

    if (password && confirmPassword) {
      confirmPassword.addEventListener("input", () => {
        if (password.value !== confirmPassword.value) {
          confirmPassword.setCustomValidity("Passwords do not match")
        } else {
          confirmPassword.setCustomValidity("")
        }
      })

      password.addEventListener("input", () => {
        if (confirmPassword.value) {
          if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity("Passwords do not match")
          } else {
            confirmPassword.setCustomValidity("")
          }
        }
      })
    }
  })
}

/**
 * Initialize DataTables with custom styling and responsive features
 */
function initDataTables() {
  const dataTables = document.querySelectorAll(".datatable")

  if (dataTables.length > 0 && typeof $.fn.DataTable !== "undefined") {
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
 * Initialize auto-dismiss for alerts
 */
function initAlertsDismiss() {
  const autoAlerts = document.querySelectorAll(".alert[data-auto-dismiss]")

  autoAlerts.forEach((alert) => {
    const timeout = Number.parseInt(alert.getAttribute("data-auto-dismiss")) || 5000
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(alert)
      bsAlert.close()
    }, timeout)
  })
}

/**
 * Initialize password toggle visibility
 */
function initPasswordToggle() {
  const passwordToggles = document.querySelectorAll(".password-toggle")

  passwordToggles.forEach((toggle) => {
    toggle.addEventListener("click", () => {
      const input = toggle.previousElementSibling
      const type = input.getAttribute("type") === "password" ? "text" : "password"
      input.setAttribute("type", type)

      // Toggle icon
      if (type === "text") {
        toggle.innerHTML = '<i class="bi bi-eye-slash"></i>'
      } else {
        toggle.innerHTML = '<i class="bi bi-eye"></i>'
      }
    })
  })
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
 * Initialize dark mode functionality
 */
function initDarkMode() {
  const darkModeToggle = document.getElementById("darkModeToggle")
  const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)")

  // Check for saved theme preference or use the system preference
  const savedTheme = localStorage.getItem("theme")
  const systemTheme = prefersDarkScheme.matches ? "dark" : "light"
  const theme = savedTheme || systemTheme

  // Apply the theme
  if (theme === "dark") {
    document.body.classList.add("dark-mode")
    if (darkModeToggle) {
      darkModeToggle.checked = true
    }
  }

  // Toggle dark mode
  if (darkModeToggle) {
    darkModeToggle.addEventListener("change", () => {
      if (darkModeToggle.checked) {
        document.body.classList.add("dark-mode")
        localStorage.setItem("theme", "dark")
      } else {
        document.body.classList.remove("dark-mode")
        localStorage.setItem("theme", "light")
      }
    })
  }

  // Listen for system theme changes
  prefersDarkScheme.addEventListener("change", (e) => {
    if (!localStorage.getItem("theme")) {
      if (e.matches) {
        document.body.classList.add("dark-mode")
        if (darkModeToggle) {
          darkModeToggle.checked = true
        }
      } else {
        document.body.classList.remove("dark-mode")
        if (darkModeToggle) {
          darkModeToggle.checked = false
        }
      }
    }
  })
}

/**
 * Confirm delete action
 * @param {string} message - Confirmation message
 * @returns {boolean} - True if confirmed, false otherwise
 */
function confirmDelete(message = "Apakah Anda yakin ingin menghapus data ini?") {
  return confirm(message)
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

// Declare bootstrap variable to avoid undefined error
const bootstrap = window.bootstrap

// Declare $ variable to avoid undefined error
const $ = window.jQuery
