// ── Color utilities ───────────────────────────────────
export const PALETTE = [
    "#2563eb",
    "#7c3aed",
    "#059669",
    "#d97706",
    "#dc2626",
    "#0891b2",
    "#65a30d",
    "#9333ea",
    "#c2410c",
    "#0284c7",
];

export const BADGE_STYLES = {
    Major: "bg-indigo-50 text-indigo-700",
    "General Ed.": "bg-emerald-50 text-emerald-700",
    Internship: "bg-amber-50 text-amber-700",
};

export function seededColor(courseId) {
    // MurmurHash3 finalizer — excellent avalanche for small integers
    let h = courseId;
    h = Math.imul(h ^ (h >>> 16), 0x85ebca6b) >>> 0;
    h = Math.imul(h ^ (h >>> 13), 0xc2b2ae35) >>> 0;
    h = (h ^ (h >>> 16)) >>> 0;
    return PALETTE[h % PALETTE.length];
}
