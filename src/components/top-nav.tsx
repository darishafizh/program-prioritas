"use client";

import { useAuth } from "@/lib/auth-context";
import { Fish, LogOut, Menu, Bell, Search } from "lucide-react";
import { Button } from "@/components/ui/button";

interface TopNavProps {
  onMenuToggle: () => void;
}

export function TopNav({ onMenuToggle }: TopNavProps) {
  const { user, logout } = useAuth();

  return (
    <header className="h-16 bg-white/80 backdrop-blur-xl border-b border-gray-100 flex items-center justify-between px-4 lg:px-6 shadow-sm shadow-gray-200/50 z-20 sticky top-0">
      {/* Left side */}
      <div className="flex items-center gap-3">
        <button
          onClick={onMenuToggle}
          className="lg:hidden p-2 rounded-xl hover:bg-gray-100 text-gray-500 transition-colors"
        >
          <Menu className="w-5 h-5" />
        </button>

        <div className="hidden sm:flex items-center gap-2">
          <div className="w-8 h-8 bg-gradient-to-br from-navy-600 to-accent rounded-lg flex items-center justify-center">
            <Fish className="w-4 h-4 text-white" />
          </div>
          <div>
            <h2 className="text-sm font-bold text-navy-800 leading-none tracking-tight">Roren One</h2>
            <p className="text-[10px] text-gray-400 leading-none mt-0.5">Portal KKP RI</p>
          </div>
        </div>

        {/* Breadcrumb separator */}
        <div className="hidden md:block h-6 w-px bg-gray-200 mx-2" />

        {/* Search */}
        <div className="hidden md:flex items-center gap-2 bg-gray-100/50 hover:bg-gray-100 focus-within:bg-white focus-within:ring-2 focus-within:ring-accent/20 rounded-xl px-3 py-2 w-64 transition-all duration-300">
          <Search className="w-4 h-4 text-gray-400" />
          <input
            type="text"
            placeholder="Cari menu atau modul..."
            className="bg-transparent text-sm text-gray-600 placeholder-gray-400 outline-none w-full"
          />
        </div>
      </div>

      {/* Right side */}
      <div className="flex items-center gap-2">
        {/* Notifications */}
        <button className="relative p-2 rounded-xl hover:bg-gray-100 text-gray-500 transition-colors">
          <Bell className="w-5 h-5" />
          <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full" />
        </button>

        <div className="h-6 w-px bg-gray-200 mx-1" />

        {/* User info */}
        <div className="flex items-center gap-3">
          <div className="hidden sm:block text-right">
            <p className="text-sm font-semibold text-navy-800 leading-none">
              {user?.name || "Admin"}
            </p>
            <p className="text-[11px] text-gray-400 leading-none mt-1">
              {user?.email || "admin@kkp.go.id"}
            </p>
          </div>
          <div className="w-9 h-9 bg-gradient-to-br from-navy-600 to-accent rounded-xl flex items-center justify-center text-white font-semibold text-sm shadow-md shadow-accent/10">
            {user?.name?.charAt(0) || "A"}
          </div>
          <Button
            variant="ghost"
            size="sm"
            onClick={logout}
            className="text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200"
          >
            <LogOut className="w-4 h-4" />
            <span className="hidden sm:inline ml-1 text-xs">Keluar</span>
          </Button>
        </div>
      </div>
    </header>
  );
}
