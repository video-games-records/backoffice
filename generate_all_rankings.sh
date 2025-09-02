#!/bin/bash

# Script to generate all rankings from January 1st, 2011 to today
# Usage: ./generate_all_rankings.sh [game|player|both]

set -e

CONSOLE_PATH="php bin/console vgr:rankings:generate"
TYPE=${1:-both}
START_YEAR=2021
CURRENT_YEAR=$(date +%Y)
CURRENT_MONTH=$(date +%m)
CURRENT_WEEK=$(date +%W)

echo "🚀 Starting rankings generation from 2011 to $(date +%Y)"
echo "📊 Type: $TYPE"
echo ""

# Function to generate rankings for a specific type
generate_rankings() {
    local type=$1
    echo "🎯 Generating $type rankings..."
    
    # Generate yearly rankings
    echo "📅 Generating yearly $type rankings..."
    for year in $(seq $START_YEAR $CURRENT_YEAR); do
        echo "  → Year $year"
        $CONSOLE_PATH $type year --year=$year
    done
    
    # Generate monthly rankings
    echo "📆 Generating monthly $type rankings..."
    for year in $(seq $START_YEAR $CURRENT_YEAR); do
        local max_month=12
        if [ $year -eq $CURRENT_YEAR ]; then
            max_month=$CURRENT_MONTH
        fi
        
        for month in $(seq 1 $max_month); do
            printf "  → %d-%02d\n" $year $month
            $CONSOLE_PATH $type month --year=$year --month=$month
        done
    done
    
    # Generate weekly rankings
    echo "📊 Generating weekly $type rankings..."
    for year in $(seq $START_YEAR $CURRENT_YEAR); do
        local max_week=52
        
        # Get the actual number of weeks for this year
        local last_week=$(date -d "$year-12-31" +%W)
        if [ $last_week -eq 1 ]; then
            # Last week of year belongs to next year
            max_week=52
        else
            max_week=$last_week
        fi
        
        # If current year, limit to current week
        if [ $year -eq $CURRENT_YEAR ]; then
            max_week=$CURRENT_WEEK
        fi
        
        for week in $(seq 1 $max_week); do
            printf "  → %d-W%02d\n" $year $week
            $CONSOLE_PATH $type week --year=$year --week=$week
        done
    done
    
    echo "✅ Completed $type rankings generation"
    echo ""
}

# Main execution
case $TYPE in
    "game")
        generate_rankings "game"
        ;;
    "player")
        generate_rankings "player"
        ;;
    "both")
        generate_rankings "game"
        generate_rankings "player"
        ;;
    *)
        echo "❌ Invalid type: $TYPE"
        echo "Usage: $0 [game|player|both]"
        exit 1
        ;;
esac

echo "🎉 All rankings generation completed successfully!"
echo "📈 Summary:"
echo "   - Years: $START_YEAR to $CURRENT_YEAR"
echo "   - Months: January 2011 to $(date +%B\ %Y)"
echo "   - Weeks: Week 1/2011 to Week $CURRENT_WEEK/$CURRENT_YEAR"
echo ""
echo "💡 To clean old rankings, add --clean flag to individual commands"