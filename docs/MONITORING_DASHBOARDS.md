# Hardban Records Lab (HRL) - Enterprise Grafana & Prometheus Dashboard Blueprint
### Version 1.0.0 | Operational Visualization Standards 2026

This document presents the complete JSON dashboard configurations and Prometheus Query (PromQL) metrics recommended for monitoring the Commercial Music Licensing Platform (CMLP) in production environments.

---

## 1. Core System Metrics Dashboard (PromQL Matrix)

These PromQL queries form the foundation of our custom Grafana health alerts.

### 1.1 CPU & Memory Footprint Indicators
- **Node CPU Utilization (Percent)**:
  ```promql
  100 - (avg by (instance) (rate(node_cpu_seconds_total{mode="idle"}[5m])) * 100)
  ```
- **Node Memory Usage Ratio (Percent)**:
  ```promql
  ((node_memory_MemTotal_bytes - node_memory_MemAvailable_bytes) / node_memory_MemTotal_bytes) * 100
  ```

### 1.2 Upstream Connection and HTTP Status Indicators
- **Request Load per Second**:
  ```promql
  sum(rate(http_requests_total[5m])) by (route, method)
  ```
- **API Error Rate (5xx Server Exceptions)**:
  ```promql
  sum(rate(http_requests_total{status=~"5.."}[5m])) / sum(rate(http_requests_total[5m])) * 100
  ```
- **95th Percentile Latency SLA Verification**:
  ```promql
  histogram_quantile(0.95, sum(rate(http_request_duration_seconds_bucket[5m])) by (le))
  ```

---

## 2. Grafana Dashboard JSON Schema Template

Below is a production-compatible dashboard schema config. To integrate, copy this block directly and import it under **Grafana -> Dashboards -> Import -> Or paste JSON**.

```json
{
  "annotations": {
    "list": [
      {
        "builtIn": 1,
        "datasource": {
          "type": "datasource",
          "uid": "grafana"
        },
        "enable": true,
        "hide": true,
        "name": "Annotations & Alerts",
        "type": "dashboard"
      }
    ]
  },
  "editable": true,
  "fiscalYearStartMonth": 0,
  "graphTooltip": 1,
  "id": 1021,
  "links": [],
  "liveNow": false,
  "panels": [
    {
      "collapsed": false,
      "gridPos": {
        "h": 3,
        "w": 24,
        "x": 0,
        "y": 0
      },
      "id": 1,
      "title": "System Uptime Status Row",
      "type": "row"
    },
    {
      "datasource": {
        "type": "prometheus",
        "uid": "hrl-prometheus-v1"
      },
      "fieldConfig": {
        "defaults": {
          "color": {
            "mode": "thresholds"
          },
          "mappings": [],
          "thresholds": {
            "mode": "absolute",
            "steps": [
              {
                "color": "green",
                "value": null
              },
              {
                "color": "yellow",
                "value": 1
              },
              {
                "color": "red",
                "value": 3
              }
            ]
          },
          "unit": "short"
        },
        "overrides": []
      },
      "gridPos": {
        "h": 6,
        "w": 8,
        "x": 0,
        "y": 3
      },
      "id": 2,
      "options": {
        "colorMode": "value",
        "graphMode": "area",
        "justifyMode": "auto",
        "orientation": "auto",
        "reduceOptions": {
          "calcs": [
            "lastNotNull"
          ],
          "fields": "",
          "values": false
        },
        "textMode": "auto"
      },
      "targets": [
        {
          "datasource": {
            "type": "prometheus",
            "uid": "hrl-prometheus-v1"
          },
          "editorMode": "code",
          "expr": "up{job=\"hrl-licensing-platform\"}",
          "legendFormat": "App Health status",
          "range": true,
          "refId": "A"
        }
      ],
      "title": "CMLP App Core Service Status",
      "type": "stat"
    },
    {
      "datasource": {
        "type": "prometheus",
        "uid": "hrl-prometheus-v1"
      },
      "fieldConfig": {
        "defaults": {
          "color": {
            "mode": "palette-classic"
          },
          "custom": {
            "axisBorderShow": false,
            "axisCenteredZero": false,
            "axisColorMode": "text",
            "axisLabel": "",
            "axisPlacement": "auto",
            "barAlignment": 0,
            "drawStyle": "line",
            "fillOpacity": 10,
            "gradientMode": "none",
            "hideFrom": {
              "legend": false,
              "tooltip": false,
              "viz": false
            },
            "lineInterpolation": "smooth",
            "lineWidth": 1,
            "pointSize": 5,
            "scaleDistribution": {
              "type": "linear"
            },
            "showPoints": "never",
            "spanNulls": false,
            "stacking": {
              "group": "A",
              "mode": "none"
            },
            "thresholdsStyle": {
              "mode": "off"
            }
          },
          "mappings": [],
          "unit": "ms"
        }
      },
      "gridPos": {
        "h": 6,
        "w": 16,
        "x": 8,
        "y": 3
      },
      "id": 3,
      "options": {
        "legend": {
          "calcs": [],
          "displayMode": "list",
          "placement": "bottom",
          "showLegend": true
        },
        "tooltip": {
          "mode": "single",
          "sort": "none"
        }
      },
      "targets": [
        {
          "datasource": {
            "type": "prometheus",
            "uid": "hrl-prometheus-v1"
          },
          "editorMode": "code",
          "expr": "histogram_quantile(0.95, sum(rate(http_request_duration_seconds_bucket[5m])) by (le)) * 1000",
          "legendFormat": "p95 Latency",
          "range": true,
          "refId": "A"
        }
      ],
      "title": "API Request Round-trip Latency (P95 SLA tracker)",
      "type": "timeseries"
    }
  ],
  "schemaVersion": 38,
  "style": "dark",
  "tags": [
    "cmlp",
    "hrl",
    "prod"
  ],
  "time": {
    "from": "now-6h",
    "to": "now"
  },
  "timepicker": {},
  "timezone": "browser",
  "title": "Hardban Records Lab - Production Platform Monitor",
  "uid": "hrl_prod_dash",
  "version": 1
}
```

---

## 3. Alerts & Dispatcher Routing Configuration

We leverage the Prometheus Alertmanager framework (`/etc/alertmanager/alertmanager.yml`) for instant dispatching.

```yaml
global:
  resolve_timeout: 5m

route:
  group_by: ['alertname', 'cluster', 'service']
  group_wait: 10s
  group_interval: 10s
  repeat_interval: 1h
  receiver: 'default-slack-alerts'

receivers:
- name: 'default-slack-alerts'
  slack_configs:
  - api_url: '***REDACTED***'
    channel: '#hrl-system-alerts'
    send_resolved: true
    title: '[{{ .Status | toUpper }}] CMLP Core Cluster Alert'
    text: >-
      *Alert:* {{ .CommonAnnotations.summary }}
      *Severity:* {{ .CommonLabels.severity }}
      *Description:* {{ .CommonAnnotations.description }}
```
