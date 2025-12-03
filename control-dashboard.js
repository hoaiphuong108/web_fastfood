// control.js
"use strict";

document.addEventListener("DOMContentLoaded", function () {
  // ---------- 1) Chart (Chart.js phải load trước file này) ----------
  (function initRevenueChart() {
    const canvas = document.getElementById("revenueChart");
    if (!canvas) return;
    const ctx = canvas.getContext ? canvas.getContext("2d") : canvas;
    try {
      new Chart(ctx, {
        type: "line",
        data: {
          labels: ["T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8", "T9", "T10"],
          datasets: [
            {
              label: "Doanh thu (triệu đồng)",
              data: [12, 15, 20, 18, 25, 30, 28, 35, 40, 38],
              borderColor: "#2d98da",
              backgroundColor: "rgba(45,152,218,0.2)",
              fill: true,
              tension: 0.3,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: { y: { beginAtZero: true } },
        },
      });
    } catch (err) {
      console.error("Chart khởi tạo lỗi:", err);
    }
  })();

  // ---------- 2) Typing effect ----------
  (function initTypingEffect() {
    const textArray = [
      "Dashboard!!",
      "Xin chào!!  Admin",
      "...cập nhật thông tin mới nhất...",
    ];
    const typingSpeed = 120;
    const erasingSpeed = 60;
    const delayBetween = 1000;

    const typingElement = document.querySelector(".typing-text");
    if (!typingElement) return;

    let textIndex = 0,
      charIndex = 0,
      isErasing = false;

    function typeEffect() {
      const currentText = textArray[textIndex];

      if (!isErasing && charIndex <= currentText.length) {
        typingElement.textContent = currentText.substring(0, charIndex++);
        setTimeout(typeEffect, typingSpeed);
      } else if (isErasing && charIndex >= 0) {
        typingElement.textContent = currentText.substring(0, charIndex--);
        setTimeout(typeEffect, erasingSpeed);
      } else {
        isErasing = !isErasing;
        if (!isErasing) textIndex = (textIndex + 1) % textArray.length;
        setTimeout(typeEffect, delayBetween);
      }
    }

    // start a little delayed so UI ổn
    setTimeout(typeEffect, 300);
  })();

  // ---------- 3) Animated counter (count up) ----------
  (function initCounters() {
    // elements that should animate: data-target attribute chứa số đích
    const counters = document.querySelectorAll(".countup");
    if (!counters || counters.length === 0) return;

    // helper: format number with commas
    function formatNumber(n) {
      return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    counters.forEach((el) => {
      const targetRaw = el.getAttribute("data-target") || el.textContent;
      // try parse int (remove commas/dots)
      const target = parseInt(String(targetRaw).replace(/[^\d]/g, ""), 10) || 0;
      const duration = parseInt(el.getAttribute("data-duration") || 1500, 10); // ms
      const start = 0;
      const startTime = performance.now();

      function step(now) {
        const progress = Math.min((now - startTime) / duration, 1);
        // easeOutQuad
        const eased = 1 - (1 - progress) * (1 - progress);
        const current = Math.floor(start + (target - start) * eased);
        el.textContent = formatNumber(current);
        if (progress < 1) {
          requestAnimationFrame(step);
        } else {
          el.textContent = formatNumber(target); // ensure exact end value
        }
      }

      // Optionally: animate only when visible (simple check)
      function isInViewport(elem) {
        const rect = elem.getBoundingClientRect();
        return rect.top < window.innerHeight && rect.bottom >= 0;
      }

      if (isInViewport(el)) {
        requestAnimationFrame(step);
      } else {
        // nếu chưa thấy trên màn hình, animate khi scroll đến
        const onScroll = function () {
          if (isInViewport(el)) {
            requestAnimationFrame(step);
            window.removeEventListener("scroll", onScroll);
          }
        };
        window.addEventListener("scroll", onScroll);
      }
    });
  })();
});
const ctx = document.getElementById("revenueChart").getContext("2d");

// tạo gradient màu nền mềm hơn
const gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, "rgba(46, 204, 113, 0.3)");
gradient.addColorStop(1, "rgba(46, 204, 113, 0.05)");

new Chart(ctx, {
  type: "line",
  data: {
    labels: ["T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8", "T9", "T10"],
    datasets: [
      {
        label: "Doanh thu (triệu đồng)",
        data: [12, 15, 20, 18, 25, 30, 28, 34, 40, 38],
        fill: true,
        backgroundColor: gradient,
        borderColor: "#2ecc71",
        borderWidth: 3,
        tension: 0.4,
        pointBackgroundColor: "#fff",
        pointBorderColor: "#27ae60",
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
        pointHoverBorderWidth: 3,
        pointHoverBackgroundColor: "#27ae60",
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: "#2c3e50",
        titleFont: { size: 14, weight: "600" },
        bodyFont: { size: 13 },
        padding: 10,
        borderWidth: 1,
        borderColor: "#2ecc71",
        cornerRadius: 10,
        displayColors: false,
      },
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { color: "#555", stepSize: 5 },
        grid: { color: "rgba(0,0,0,0.05)" },
      },
      x: {
        ticks: { color: "#555" },
        grid: { display: false },
      },
    },
  },
});
