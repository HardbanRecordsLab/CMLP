# Technical Architecture: Custom Components

## System Overview

This document defines the technical architecture for building the 10 critical components missing from open-source libraries.

---

## Architecture Principles

### 1. Alpine.js First
All components use Alpine.js x-data patterns for state management and interactivity.

### 2. Progressive Enhancement
Components work without JavaScript (basic functionality), enhanced with Alpine.js.

### 3. Component Composition
Small, focused components combine to build complex features.

### 4. Event-Driven Communication
Custom DOM events for cross-component coordination.

### 5. Storage Abstraction
localStorage for persistence, with swapable backend adapters.

---

## Project Structure

```
CMLP-DesignSystem/
├── components/
│   ├── html/
│   │   ├── primitive/           # Reusable primitives
│   │   │   ├── command-palette.html
│   │   │   ├── drag-handle.html
│   │   │   ├── panel.html
│   │   │   └── search-input.html
│   │   ├── kanban/
│   │   │   ├── kanban-board.html
│   │   │   ├── kanban-column.html
│   │   │   └── kanban-card.html
│   │   ├── form-builder/
│   │   │   ├── form-builder.html
│   │   │   ├── field-text.html
│   │   │   ├── field-select.html
│   │   │   └── field-date.html
│   │   ├── chat/
│   │   ├── audio-player/
│   │   ├── gantt/
│   │   ├── panels/
│   │   ├── mindmap/
│   │   ├── presentation/
│   │   └── ebook/
│   └── js/
│       ├── command-palette.js
│       ├── kanban.js
│       ├── form-builder.js
│       ├── chat.js
│       └── utils.js
├── lib/
│   ├── fuse-search.js           # Fuzzy search wrapper
│   ├── drag-drop.js             # DnD abstraction
│   ├── storage.js               # localStorage wrapper
│   └── validation.js            # Form validation
└── styles/
    └── custom-components.css
```

---

## Core Patterns

### Pattern 1: Component State Management

Every component follows this standard pattern:

```javascript
x-data="componentName({
  // State
  state: {},
  
  // Computed
  get computed() { return {} },
  
  // Methods
  methods: {},
  
  // Lifecycle
  init() { this.setup() },
  
  // Watchers
  'state.observable': function() { }
})"
```

### Pattern 2: Event System

```javascript
// Dispatch event
$dispatch('component:action', { data })

// Listen event
@component:action.window="handleAction"

// Global event bus (Alpine store)
Alpine.store('events', {
  listeners: {},
  on(event, callback) { /* ... */ },
  emit(event, data) { /* ... */ }
})
```

### Pattern 3: Storage Pattern

```javascript
class ComponentStorage {
  prefix = 'cmlp_'
  
  get(key) {
    const data = localStorage.getItem(this.prefix + key)
    return data ? JSON.parse(data) : null
  }
  
  set(key, value) {
    localStorage.setItem(this.prefix + key, JSON.stringify(value))
  }
  
  remove(key) {
    localStorage.removeItem(this.prefix + key)
  }
}
```

---

## Component Specifications

### 1. Command Palette

**File:** `components/html/command-palette.html`

```javascript
x-data="commandPalette({
  open: false,
  query: '',
  selectedIndex: 0,
  commands: [],
  recent: [],
  theme: 'dark'
})"
```

**Features:**
- Global hotkey (Cmd+K / Ctrl+K)
- Fuzzy search (Fuse.js)
- Command grouping
- Recent commands
- Keyboard navigation (↑/↓/Enter/Esc)
- Nested menu support

**API:**
```javascript
{
  open() { this.open = true },
  close() { this.open = false },
  toggle() { this.open = !this.open },
  search(query) { /* fuzzy match */ },
  execute(command) { /* run action */ },
  register(command) { /* add command */ }
}
```

**Accessibility:**
- role="dialog" aria-modal="true"
- aria-labelledby="command-palette-title"
- Focus trap
- aria-activedescendant for selected item

**Integration:**
```javascript
// Global registration
window.CMLP = {
  commandPalette: {
    register: (commands) => { /* ... */ },
    open: () => { /* ... */ }
  }
}
```

---

### 2. Kanban Board

**File:** `components/html/kanban/kanban-board.html`

```javascript
x-data="kanbanBoard({
  columns: [],
  draggedCard: null,
  draggedFrom: null,
  storageKey: 'kanban-board-1'
})"
```

**Features:**
- Drag & drop (SortableJS)
- Column CRUD
- Card CRUD
- Color labels/tags
- Filter/search cards
- Local storage sync
- Undo/redo (optional)

**Architecture:**
```javascript
{
  columns: [
    { id, title, cards: [{id, title, description, tags, order}] }
  ],
  
  // DnD
  dragStart(card, column) { this.draggedCard = card; this.draggedFrom = column },
  dragOver(column) { /* visual feedback */ },
  drop(column) { /* move card */ },
  
  // CRUD
  addCard(columnId, card) { /* ... */ },
  updateCard(cardId, data) { /* ... */ },
  deleteCard(cardId) { /* ... */ },
  
  // Columns
  addColumn(title) { /* ... */ },
  updateColumn(id, title) { /* ... */ },
  deleteColumn(id) { /* ... */ }
}
```

**Performance:**
- Virtual scrolling for 100+ cards
- Debounced storage writes
- requestAnimationFrame for animations

---

### 3. Schema/JSON Form Builder

**File:** `components/html/form-builder/form-builder.html`

```javascript
x-data="formBuilder({
  schema: { fields: [] },
  data: {},
  errors: {},
  touched: {}
})"
```

**Schema Definition:**
```javascript
{
  fields: [
    {
      type: 'text' | 'email' | 'password' | 'number' | 'textarea' | 'select' | 'checkbox' | 'radio' | 'date',
      name: 'field_name',
      label: 'Field Label',
      placeholder: 'Enter value',
      required: true,
      validation: {
        min: 5,
        max: 100,
        pattern: '^[a-z]+$',
        custom: (value) => { return boolean }
      },
      conditional: {
        field: 'other_field',
        operator: '==' | '!=' | '>' | '<',
        value: 'expected'
      },
      options: [{value, label}] // for select/radio
    }
  ]
}
```

**Features:**
- Auto-generated forms from JSON schema
- Real-time validation
- Conditional field visibility
- Error highlighting
- Submit handling
- Data binding

**Rendering:**
```javascript
renderField(field) {
  const templates = {
    text: () => `<input type="text" x-model="data.${field.name}">`,
    select: () => `<select x-model="data.${field.name}">${options}</select>`,
    // ... more types
  }
  return templates[field.type]()
}
```

---

### 4. Live Chat Widget

**File:** `components/html/chat/chat-widget.html`

```javascript
x-data="chatWidget({
  messages: [],
  input: '',
  online: false,
  minimized: false,
  threadId: null
})"
```

**Features:**
- Real-time messaging (WebSocket mock)
- Message history
- Typing indicators
- Online status
- File attachment support
- Emoji picker (optional)
- Timestamps
- Read receipts

**Architecture:**
```javascript
{
  messages: [
    { id, text, sender: 'user'|'agent', timestamp, status }
  ],
  
  send() { /* emit message */ },
  receive(message) { /* add to array */ },
  typing() { /* show indicator */ },
  open() { minimized = false },
  minimize() { minimized = true },
  
  // WebSocket integration
  connect() { /* ws:// */ },
  disconnect() { /* cleanup */ }
}
```

**Mock Implementation:**
```javascript
// For demo/static sites
const mockResponses = [
  "Thanks for contacting us!",
  "How can I help?",
  "Let me check that for you."
]
```

---

### 5. Audio Player

**File:** `components/html/audio-player/audio-player.html`

```javascript
x-data="audioPlayer({
  playing: false,
  currentTime: 0,
  duration: 0,
  volume: 1,
  playlist: [],
  currentTrack: 0,
  showWaveform: false
})"
```

**Features:**
- Play/pause/skip
- Progress bar with seek
- Volume control
- Playlist management
- Shuffle/repeat
- Waveform visualization (Canvas)
- Time display (current/total)

**Architecture:**
```javascript
{
  audio: new Audio(),
  
  play() { this.audio.play() },
  pause() { this.audio.pause() },
  seek(time) { this.audio.currentTime = time },
  setVolume(vol) { this.audio.volume = vol },
  
  next() { /* next track */ },
  prev() { /* previous track */ },
  
  // Waveform
  drawWaveform() { /* Canvas API */ },
  
  // Events
  onTimeUpdate() { /* update progress */ },
  onEnded() { /* auto-next */ }
}
```

---

### 6. Gantt Chart

**File:** `components/html/gantt/gantt-chart.html`

```javascript
x-data="ganttChart({
  tasks: [],
  startDate: null,
  endDate: null,
  view: 'week' | 'month',
  dependencies: []
})"
```

**Data Structure:**
```javascript
{
  tasks: [
    {
      id,
      name,
      start: 'YYYY-MM-DD',
      end: 'YYYY-MM-DD',
      progress: 0-100,
      dependencies: [task_ids],
      assignee: 'name',
      color: 'primary'
    }
  ]
}
```

**Features:**
- Timeline grid (days/weeks/months)
- Task bars with duration
- Dependency arrows (SVG)
- Drag to resize
- Drag to move
- Zoom levels
- Today marker

**Rendering:**
```javascript
render() {
  // Calculate day width based on view
  // Render grid lines
  // Render task bars with position/size
  // Draw dependency arrows
}
}
```

---

### 7. Resizable Panel System

**File:** `components/html/panels/panel-system.html`

```javascript
x-data="panelSystem({
  panels: [
    { id, size: 25, min: 10, max: 50, collapsed: false }
  ],
  direction: 'horizontal' | 'vertical',
  storageKey: 'panel-layout'
})"
```

**Features:**
- Resizable panels (drag handle)
- Min/max constraints
- Collapsible panels
- Persist layout (localStorage)
- Nested panels

**Architecture:**
```javascript
{
  panels: [
    { id: 'sidebar', size: 25, min: 10, max: 50 },
    { id: 'main', size: 75, min: 50 }
  ],
  
  startResize(panelId, event) { /* start drag */ },
  resize(panelId, delta) { /* update size */ },
  endResize() { /* save layout */ },
  
  toggle(panelId) { /* collapse/expand */ },
  
  // Flexbox-based layout
  getStyle() {
    return `display: flex; flex-direction: ${this.direction}`
  }
}
```

---

### 8. Mind Map / Flow Chart

**File:** `components/html/mindmap/mindmap.html`

```javascript
x-data="mindMap({
  nodes: [],
  connections: [],
  selectedNode: null,
  transform: { x: 0, y: 0, scale: 1 }
})"
```

**Data Structure:**
```javascript
{
  nodes: [
    { id, x, y, text, color, size }
  ],
  connections: [
    { from: nodeId, to: nodeId, label: '' }
  ]
}
```

**Features:**
- Node rendering (SVG/HTML)
- Connection lines (bezier)
- Drag/drop nodes
- Double-click to edit
- Add/delete nodes
- Zoom/pan (mouse wheel + drag)
- Export to PNG/SVG

**Rendering:**
```javascript
render() {
  // Draw connections (SVG paths)
  this.connections.forEach(conn => {
    const path = this.calculateBezier(from, to)
    // Append to SVG
  })
  
  // Draw nodes (HTML absolutely positioned)
  this.nodes.forEach(node => {
    // Append div with x/y transform
  })
}
```

---

### 9. Interactive Presentation

**File:** `components/html/presentation/presentation.html`

```javascript
x-data="presentation({
  slides: [],
  current: 0,
  fullscreen: false,
  presenterMode: false
})"
```

**Data Structure:**
```javascript
{
  slides: [
    {
      id,
      title: '',
      content: 'HTML string',
      notes: 'Presenter notes',
      background: 'color|image'
    }
  ]
}
```

**Features:**
- Slide navigation (keyboard + buttons)
- Transition effects (fade/slide/zoom)
- Fullscreen mode
- Presenter mode (notes + preview)
- Slide thumbnails
- Auto-play

**Rendering:**
```javascript
renderSlide(index) {
  return `
    <div x-show="current === ${index}" 
         x-transition:enter="transition"
         x-transition:enter-start="opacity-0">
      ${this.slides[index].content}
    </div>
  `
}
```

---

### 10. eBook Reader

**File:** `components/html/ebook/ebook-reader.html`

```javascript
x-data="ebookReader({
  book: null,
  currentPage: 0,
  totalPages: 0,
  bookmarks: [],
  fontSize: 16,
  theme: 'light'
})"
```

**Features:**
- EPUB parsing (epub.js integration)
- Page turning (CSS transforms)
- Font size adjustment
- Theme switching (light/dark/sepia)
- Bookmarks
- Reading progress
- Table of contents

**Architecture:**
```javascript
{
  book: null, // ePub instance
  rendition: null, // ePub rendition
  
  async load(url) {
    this.book = await ePub(url)
    this.rendition = this.book.renderTo(element)
    await this.rendition.display()
  },
  
  next() { this.rendition.next() },
  prev() { this.rendition.prev() },
  
  addBookmark(cfi) { /* save */ },
  gotoBookmark(cfi) { /* restore */ },
  
  setFontSize(size) { /* update style */ }
}
```

---

## Shared Utilities

### Storage Manager

**File:** `lib/storage.js`

```javascript
export class StorageManager {
  constructor(prefix = 'cmlp_') {
    this.prefix = prefix
  }
  
  get(key, defaultValue = null) {
    try {
      const item = localStorage.getItem(this.prefix + key)
      return item ? JSON.parse(item) : defaultValue
    } catch {
      return defaultValue
    }
  }
  
  set(key, value) {
    localStorage.setItem(this.prefix + key, JSON.stringify(value))
  }
  
  remove(key) {
    localStorage.removeItem(this.prefix + key)
  }
  
  clear() {
    Object.keys(localStorage)
      .filter(k => k.startsWith(this.prefix))
      .forEach(k => localStorage.removeItem(k))
  }
}
```

### Search Service

**File:** `lib/fuse-search.js`

```javascript
import Fuse from 'fuse.js'

export class SearchService {
  constructor(items, options = {}) {
    this.fuse = new Fuse(items, {
      keys: ['title', 'description', 'tags'],
      threshold: 0.3,
      ...options
    })
  }
  
  search(query) {
    return this.fuse.search(query).map(r => r.item)
  }
}
```

### Drag & Drop Manager

**File:** `lib/drag-drop.js`

```javascript
export class DragDropManager {
  constructor(element, options = {}) {
    this.element = element
    this.options = options
    this.dragged = null
    this.setup()
  }
  
  setup() {
    this.element.draggable = true
    this.element.addEventListener('dragstart', this.onDragStart.bind(this))
    this.element.addEventListener('dragend', this.onDragEnd.bind(this))
  }
  
  onDragStart(e) {
    this.dragged = e.target
    e.dataTransfer.effectAllowed = 'move'
  }
  
  onDragEnd(e) {
    this.dragged = null
  }
}
```

### Validation Engine

**File:** `lib/validation.js`

```javascript
export class ValidationEngine {
  static rules = {
    required: (v) => !!v || 'Required',
    email: (v) => /.+@.+\..+/.test(v) || 'Invalid email',
    min: (v, n) => v.length >= n || `Min ${n} chars`,
    max: (v, n) => v.length <= n || `Max ${n} chars`,
    pattern: (v, p) => p.test(v) || 'Invalid format'
  }
  
  static validate(schema, data) {
    const errors = {}
    schema.fields.forEach(field => {
      if (field.validation) {
        const value = data[field.name]
        for (const [rule, param] of Object.entries(field.validation)) {
          const validator = this.rules[rule]
          if (validator) {
            const error = validator(value, param)
            if (error) errors[field.name] = error
          }
        }
      }
    })
    return errors
  }
}
```

---

## Testing Strategy

### Unit Tests
- Component state management
- Validation rules
- Search/filter logic
- Storage operations

### Integration Tests
- Drag & drop flows
- Form submission
- Chat messaging
- Audio playback controls

### Accessibility Tests
- Keyboard navigation
- ARIA attributes
- Screen reader compatibility
- Focus management

### Performance Tests
- Drag/drop frame rate (60fps)
- Large dataset rendering (1000+ items)
- Memory leaks (long sessions)

---

## Build Configuration

### Package.json additions:

```json
{
  "dependencies": {
    "sortablejs": "^1.15.0",
    "fuse.js": "^7.0.0"
  },
  "devDependencies": {
    "epubjs": "^0.3.93",
    "wavesurfer.js": "^7.0.0"
  }
}
```

### Tailwind Config:

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        'command-bg': '#1e1e1e',
        'kanban-col': '#f3f4f6',
        'chat-bubble': '#e5e7eb'
      }
    }
  }
}
```

---

## Deployment Strategy

### Lazy Loading
```javascript
// Load components on-demand
const loadKanban = () => import('./components/kanban/kanban-board.html')
```

### Tree Shaking
```javascript
// Export individual components
export { CommandPalette } from './command-palette'
export { KanbanBoard } from './kanban'
```

### Bundle Analysis
- Monitor core bundle size (<50kb target)
- Split vendor chunks
- Optimize images/assets

---

## Documentation Requirements

Each component requires:

1. **README.md** — Overview, features, installation
2. **API.md** — Public methods, events, properties
3. **EXAMPLES.md** — 5+ usage examples
4. **ACCESSIBILITY.md** — ARIA guide, keyboard shortcuts
5. **PROPS.md** — Configuration options
6. **AI_PROMPT.md** — Prompt templates for AI generation

---

## Migration Path

### Version 1.0 (Current)
- Base Alpine.js components
- localStorage persistence
- No external dependencies (except Fuse.js)

### Version 2.0 (Future)
- Web Component wrapper
- React/Vue bindings
- Backend API integration
- Real-time sync

---

*Document Version: 1.0*
*Last Updated: 2026-07-19*
*Owner: CMLP Architecture Team*