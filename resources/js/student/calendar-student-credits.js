import { seededColor, BADGE_STYLES } from "./calendar-student-colors.js";

// ── Credit Summary ───────────────────────────────────
export function updateCredits() {
    const selected = allCourses.filter((c) => selectedIds.has(c.id));
    const total = selected.reduce((s, c) => s + (c.credits || 0), 0);
    const pct = Math.min(100, Math.round((total / CREDIT_GOAL) * 100));
    document.getElementById("totalCreditsNum").textContent = total;
    document.getElementById("totalProgressBar").style.width = pct + "%";
    const numEl = document.getElementById("totalCreditsNum");
    const barEl = document.getElementById("totalProgressBar");
    if (total >= CREDIT_GOAL) {
        numEl.className = "text-4xl font-bold text-emerald-500";
        barEl.className = "progress-fill bg-emerald-500";
    } else {
        numEl.className = "text-4xl font-bold text-red-500";
        barEl.className = "progress-fill bg-red-500";
    }
    const gap = CREDIT_GOAL - total;
    const gapEl = document.getElementById("creditGapText");
    if (gap <= 0) {
        gapEl.textContent = "Credit goal reached!";
        document
            .getElementById("creditGapMsg")
            .querySelector("svg").style.display = "none";
        document.getElementById("creditGapMsg").style.color = "#059669";
    } else {
        gapEl.textContent = `You need ${gap} more credits`;
        document
            .getElementById("creditGapMsg")
            .querySelector("svg").style.display = "";
        document.getElementById("creditGapMsg").style.color = "";
    }
    // Category breakdown
    const breakdown = document.getElementById("categoryBreakdown");
    breakdown.innerHTML = Object.entries(CATEGORIES)
        .map(([cat, cfg]) => {
            const earned = selected
                .filter((c) => c.category === cat)
                .reduce((s, c) => s + (c.credits || 0), 0);
            const p = Math.min(100, Math.round((earned / cfg.goal) * 100));
            return `
  <div>
    <div class="flex items-center justify-between mb-1">
      <span class="text-xs text-gray-600">${cat}</span>
      <div class="flex items-center gap-1">
        <span class="text-xs font-semibold text-gray-900">${earned} / ${cfg.goal}</span>
        <button class="text-gray-300 hover:text-gray-500 transition-colors">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
      </div>
    </div>
    <div class="progress-bar">
      <div class="progress-fill" style="width:${p}%; background:${earned >= cfg.goal ? "#059669" : cfg.color}"></div>
    </div>
  </div>`;
        })
        .join("");
    // Warning banner
    const warn = document.getElementById("creditWarning");
    if (gap > 0) {
        warn.classList.remove("hidden");
        warn.classList.add("flex");
        document.getElementById("warningBody").textContent =
            `You need ${gap} more credits in total. Please add more courses or adjust your selections.`;
    } else {
        warn.classList.add("hidden");
        warn.classList.remove("flex");
    }
}

// ── Legend ───────────────────────────────────────────
export function renderLegend() {
    const legend = document.getElementById("legend");
    const selected = allCourses.filter((c) => selectedIds.has(c.id));
    if (!selected.length) {
        legend.innerHTML = "";
        return;
    }
    legend.innerHTML = selected
        .map(
            (c) => `
<div class="flex items-center gap-1.5">
  <div class="legend-dot" style="background:${seededColor(c.id)}"></div>
  <span class="text-xs text-gray-500">${c.title}</span>
</div>`,
        )
        .join("");
}

// ── Suggested ────────────────────────────────────────
export function renderSuggested() {
    const unselected = allCourses
        .filter((c) => !selectedIds.has(c.id))
        .slice(0, 3);
    const el = document.getElementById("suggestedList");
    if (!unselected.length) {
        el.innerHTML =
            '<p class="text-xs text-gray-400">All courses selected.</p>';
        return;
    }
    el.innerHTML = unselected
        .map((c) => {
            const badge =
                BADGE_STYLES[c.category] || "bg-gray-100 text-gray-600";
            return `
  <div class="border border-gray-100 rounded-xl p-3 hover:border-gray-200 transition-colors">
    <div class="flex items-start justify-between gap-2">
      <div class="flex-1">
        <div class="flex items-center gap-1.5 mb-0.5">
          <div class="w-1 h-10 rounded-full flex-shrink-0" style="background:${seededColor(c.id)}"></div>
          <div>
            <p class="text-sm font-medium text-gray-900 leading-snug">${c.title}</p>
            <div class="flex items-center gap-1.5 mt-0.5">
              ${c.code ? `<span class="text-[11px] text-gray-400">${c.code}</span>` : ""}
              ${c.category ? `<span class="text-[10px] font-semibold px-1.5 py-0.5 rounded ${badge}">${c.category}</span>` : ""}
            </div>
            <p class="text-[11px] text-gray-400 mt-0.5">${c.credits || 0} Credits</p>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-3 flex items-center justify-between gap-2">
      <button onclick="event.stopPropagation(); openCourseDetail(${c.id})"
        class="text-xs font-semibold text-gray-600 border border-gray-200 px-2.5 py-1 rounded-lg hover:bg-gray-50 transition-colors whitespace-nowrap">
        Detail
      </button>
      <button onclick="event.stopPropagation(); toggleSelect(${c.id})"
        class="flex-shrink-0 flex items-center gap-1 text-xs font-semibold text-indigo-600 border border-indigo-200 px-2.5 py-1 rounded-lg hover:bg-indigo-50 transition-colors whitespace-nowrap">
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add
      </button>
    </div>
  </div>`;
        })
        .join("");
}
