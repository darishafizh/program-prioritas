"use client";

import { useState } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { modules, menus } from "@/lib/data";
import {
  LayoutDashboard,
  GitBranch,
  ClipboardCheck,
  ChevronDown,
  ChevronRight,
  Fish,
  PanelLeftClose,
  PanelLeft,
} from "lucide-react";
import { cn } from "@/lib/utils";

const menuIcons = {
  LayoutDashboard,
  GitBranch,
  ClipboardCheck,
};

interface SidebarProps {
  collapsed: boolean;
  onToggle: () => void;
  onMobileClose?: () => void;
}

export function Sidebar({ collapsed, onToggle, onMobileClose }: SidebarProps) {
  const pathname = usePathname();
  const [expandedMenus, setExpandedMenus] = useState<string[]>(
    menus.map((m) => m.id)
  );

  const toggleMenu = (menuId: string) => {
    setExpandedMenus((prev) =>
      prev.includes(menuId)
        ? prev.filter((id) => id !== menuId)
        : [...prev, menuId]
    );
  };

  const isActive = (menuId: string, moduleId: string) => {
    return pathname === `/portal/${menuId}/${moduleId}`;
  };

  const isMenuActive = (menuId: string) => {
    return pathname.startsWith(`/portal/${menuId}`);
  };

  return (
    <aside
      className={cn(
        "h-full bg-gradient-to-b from-navy-900 to-navy-800 flex flex-col sidebar-transition overflow-hidden shadow-2xl shadow-navy-900/50 z-30",
        collapsed ? "w-[68px]" : "w-[280px]"
      )}
    >
      {/* Logo area */}
      <div className="h-16 flex items-center px-4 border-b border-white/5">
        <div className="flex items-center gap-3 min-w-0">
          <div className="w-9 h-9 bg-gradient-to-br from-accent to-blue-400 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-accent/20">
            <Fish className="w-5 h-5 text-white" />
          </div>
          {!collapsed && (
            <div className="animate-fade-in min-w-0">
              <h1 className="text-white font-bold text-lg tracking-tight leading-none">
                Roren One
              </h1>
              <p className="text-blue-300/50 text-[10px] font-medium tracking-wider uppercase mt-0.5">
                Portal KKP
              </p>
            </div>
          )}
        </div>
      </div>

      {/* Navigation */}
      <nav className="flex-1 overflow-y-auto overflow-x-hidden py-4 px-2 space-y-1">
        {menus.map((menu) => {
          const Icon = menuIcons[menu.icon as keyof typeof menuIcons];
          const isExpanded = expandedMenus.includes(menu.id);
          const menuActive = isMenuActive(menu.id);

          return (
            <div key={menu.id}>
              {/* Menu header */}
              <button
                onClick={() => !collapsed && toggleMenu(menu.id)}
                className={cn(
                  "w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200",
                  menuActive
                    ? "bg-white/10 text-white shadow-inner shadow-white/5"
                    : "text-blue-200/70 hover:text-white hover:bg-white/5"
                )}
                title={collapsed ? menu.label : undefined}
              >
                <Icon className={cn("w-5 h-5 flex-shrink-0", menuActive && "text-accent-light")} />
                {!collapsed && (
                  <>
                    <span className="flex-1 text-left truncate">{menu.label}</span>
                    <div className="transition-transform duration-200">
                      {isExpanded ? (
                        <ChevronDown className="w-4 h-4 opacity-50" />
                      ) : (
                        <ChevronRight className="w-4 h-4 opacity-50" />
                      )}
                    </div>
                  </>
                )}
              </button>

              {/* Sub-menu items */}
              {!collapsed && isExpanded && (
                <div className="mt-1 ml-4 pl-4 border-l border-white/5 space-y-0.5">
                  {modules.map((mod) => {
                    const active = isActive(menu.id, mod.id);
                    return (
                      <Link
                        key={mod.id}
                        href={`/portal/${menu.id}/${mod.id}`}
                        onClick={onMobileClose}
                        className={cn(
                          "block px-3 py-2 rounded-lg text-[13px] transition-all duration-200",
                          active
                            ? "bg-accent/20 text-accent-light font-medium"
                            : "text-blue-200/50 hover:text-white hover:bg-white/5"
                        )}
                      >
                        <span className="flex items-center gap-2">
                          {active && (
                            <span className="w-1.5 h-1.5 bg-accent-light rounded-full flex-shrink-0" />
                          )}
                          <span className="truncate">{mod.label}</span>
                        </span>
                      </Link>
                    );
                  })}
                </div>
              )}
            </div>
          );
        })}
      </nav>

      {/* Collapse toggle */}
      <div className="p-3 border-t border-white/5">
        <button
          onClick={onToggle}
          className="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-blue-200/40 hover:text-white hover:bg-white/5 transition-all duration-200 text-sm"
        >
          {collapsed ? (
            <PanelLeft className="w-5 h-5" />
          ) : (
            <>
              <PanelLeftClose className="w-5 h-5" />
              <span>Kecilkan</span>
            </>
          )}
        </button>
      </div>
    </aside>
  );
}
