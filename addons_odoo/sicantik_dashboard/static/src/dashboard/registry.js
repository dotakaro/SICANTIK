/** @odoo-module **/

import { registry } from "@web/core/registry";
import { SicantikDashboard } from "./dashboard";

// Register dashboard component
registry.category("actions").add("sicantik_dashboard.action_dashboard", SicantikDashboard);

